<?php

namespace App\Http\Controllers;

use App\Http\Requests\HiloNotifyRequest;
use App\Http\Requests\HiloRequest;
use App\Http\Resources\HiloResource;
use App\Libraries\Binance;
use App\Models\Asset;
use App\Models\Hilo;
use Illuminate\Support\Facades\Log;

class HiloController extends BaseController
{

    public function notify(HiloNotifyRequest $request, $asset_id, $granularity)
    {
        Log::info('HiloController::notify', ['request' => $request->all()]);

        $hilo = Hilo::where('asset_id', $asset_id)->where('granularity', $granularity)->first();

        if (!$hilo) {
            return $this->sendError('Hilo data for this asset and granularity not found.');
        }

        $symbol = $hilo->asset->code;
        $price_precision = $hilo->asset->price_precision;

        // get active positions
        $positions = $hilo->asset->positions()->active()->get();

        if ($positions->count() === 0) {
            dump('No active positions for this asset and granularity.');
            Log::info('No active positions for this asset and granularity.', [
                'asset' => $symbol,
                'action' => $request->action,
                'hilo' => $hilo,
            ]);
        } else {

            foreach ($positions as $position) {

                // $binance = new Binance(config('binance.api_key'), config('binance.api_secret'));
                $binance = new Binance(config('binance.test_api_key'), config('binance.test_api_secret'), 'https://testnet.binancefuture.com');

                try {
                    $binance_position = $binance->trade()->getPosition([
                        'symbol' => $symbol,
                    ])[0];
                    dump($binance_position);
                    $has_position = $binance_position['positionAmt'] != 0 ? true : false;
                    dump($has_position);
                    $is_short = $binance_position['positionAmt'] < 0 ? true : false;
                    dump($is_short);
                } catch (\Exception $e) {
                    dump("Can't get position in Binance.");
                    Log::channel('slack')->alert("Can't get position in Binance.", [
                        'asset' => $symbol,
                        'user' => $position->user->email,
                        'error' => $e->getMessage(),
                    ]);
                    continue;
                }

                // get active order for this position
                $order = $position->orders()->active()->first();

                if ($has_position) {
                    Log::info('Position found in binance.', [
                        'asset' => $symbol,
                        'user' => $position->user->email,
                        'binance_position' => $binance_position,
                    ]);

                    // create order if no active order exists and binance position exists
                    if (!$order) {
                        $order = $position->orders()->create([
                            'side' => $is_short ? 'sell' : 'buy',
                            'entry_price' => $binance_position['entryPrice'],
                            'quantity' => $binance_position['positionAmt'],
                            'size' => ($binance_position['positionAmt'] * $binance_position['entryPrice']) * -1,
                            'started_at' => now(),
                        ]);
                    }

                    // check if position in binance is the same as notified
                    if (
                        ($binance_position['positionAmt'] > 0 && $request->action == 'buy') ||
                        ($binance_position['positionAmt'] < 0 && $request->action == 'sell')
                    ) {
                        Log::info('Position ' . $request->action . ' already exists in binance.', [
                            'asset' => $symbol,
                            'user' => $position->user->email,
                            'action' => $request->action,
                        ]);
                        continue;
                    }
                } else {
                    // close order if active order exists and binance position doesnt exists
                    if ($order) {
                        $order = $order->close();
                    }
                }

                // get price from binance
                try {
                    $binance_asset = $binance->trade()->getMarkPrice([
                        'symbol' => $symbol,
                    ]);
                } catch (\Exception $e) {
                    dump("Can't get price from Binance.");
                    Log::channel('slack')->alert("Can't get price from Binance.", [
                        'asset' => $symbol,
                        'user' => $position->user->email,
                        'error' => $e->getMessage(),
                    ]);
                    continue;
                }

                $quantity = round(($position->initial_amount - 50) / $binance_asset['markPrice'], $price_precision, PHP_ROUND_HALF_DOWN);

                if ($has_position) {
                    // check if we need to invert side
                    if (
                        ($binance_position['positionAmt'] < 0 && $request->action == 'buy') ||
                        ($binance_position['positionAmt'] > 0 && $request->action == 'sell')
                    ) {
                        $quantity *= 2;
                    }
                }

                if (!$order || $order->side != $request->action) {
                    if (!$order) {
                        // create order side
                        $log_message = 'Creating order ' . $request->action . ' for position.';
                    } elseif ($order->side != $request->action) {
                        // change order side
                        $log_message = 'Position order changed side from ' . $order->side . ' to ' . $request->action . '.';
                        $oder = $order->close();
                    }

                    // try to create order on Binance
                    try {
                        // Make sure margin type is ISOLATED
                        // $binance->trade()->postMarginType([
                        //     'symbol' => $symbol,
                        //     'marginType' => 'ISOLATED',
                        // ]);
                        // Make sure leverage is set to position leverage
                        $binance->trade()->postLeverage([
                            'symbol' => $symbol,
                            'leverage' => $position->leverage,
                        ]);
                        $result = $binance->trade()->postOrder([
                            'symbol' => $symbol,
                            'side' => strtoupper($request->action),
                            'type' => 'MARKET',
                            'quantity' => ($quantity * $position->leverage),
                        ]);
                        dump($result);
                    } catch (\Exception $e) {
                        dump("Can't create order on Binance.");
                        Log::channel('slack')->alert("Can't create order on Binance.", [
                            'asset' => $symbol,
                            'user' => $position->user->email,
                            'error' => $e->getMessage(),
                        ]);
                        continue;
                    }
                    sleep(1);

                    try {
                        $binance_position = $binance->trade()->getPosition([
                            'symbol' => $symbol,
                        ])[0];
                    } catch (\Exception $e) {
                        dump("Can't get position in Binance after its creation.");
                        Log::channel('slack')->alert("Can't get position in Binance after its creation.", [
                            'asset' => $symbol,
                            'user' => $position->user->email,
                            'error' => $e->getMessage(),
                        ]);
                        continue;
                    }

                    $position->orders()->create([
                        'side' => $request->action,
                        'entry_price' => $binance_position['entryPrice'],
                        'quantity' => $binance_position['positionAmt'],
                        'size' => ($binance_position['positionAmt'] * $binance_position['entryPrice']),
                        'started_at' => now(),
                        'external_id' => $result['orderId'],
                        'binance_client_order_id' => $result['clientOrderId'],
                    ]);
                } else {
                    // maintain order side
                    $log_message = 'Maintain position order side.';
                }

                Log::info($log_message, [
                    'asset' => $symbol,
                    'user' => $position->user->email,
                    'action' => $request->action,
                    'order' => $order,
                ]);
            }
        }

        return $this->sendResponse(null, 'Hilo notified successfully.');
    }

    public function index($asset_id)
    {
        $hilos = Asset::find($asset_id)->hilos()->get();

        return $this->sendResponse(
            HiloResource::collection($hilos),
            'Hilos retrieved successfully.'
        );
    }

    public function show($asset_id, $granularity)
    {
        $hilo = Hilo::where('asset_id', $asset_id)->where('granularity', $granularity)->first();

        if (!$hilo) {
            return $this->sendError('Hilo not found for this granularity.', 404);
        }

        return $this->sendResponse(
            new HiloResource($hilo),
            'Hilo retrieved successfully.'
        );
    }

    public function update(HiloRequest $request, $asset_id, $granularity)
    {
        if (!in_array($granularity, ['1m', '5m', '15m', '30m', '1h', '2h', '4h', '6h', '8h', '12h', '1d', '3d', '1w', '1M'])) {
            return $this->sendError('Granularity not supported.', 400);
        }

        if (Asset::find($asset_id) == null) {
            return $this->sendError('Asset not found. Please check the asset ID.');
        }

        $hilo = Hilo::where('asset_id', $asset_id)->where('granularity', $granularity)->first();

        if ($hilo) {
            Hilo::where('asset_id', $asset_id)->where('granularity', $granularity)->update($request->validated());
            $hilo = Hilo::where('asset_id', $asset_id)->where('granularity', $granularity)->first();
        } else {
            $validated = $request->validated();
            $validated['asset_id'] = $asset_id;
            $validated['granularity'] = $granularity;
            $hilo = Hilo::create($validated);
        }

        return $this->sendResponse(
            new HiloResource($hilo),
            'Hilo updated successfully.'
        );
    }
}
