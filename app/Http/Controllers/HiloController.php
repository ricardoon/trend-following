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

        // get active positions
        $positions = $hilo->asset->positions()->active()->get();

        if ($positions->count() === 0) {
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
                    // dump($binance_position);
                    $has_position = $binance_position['positionAmt'] != 0 ? true : false;
                    // dump($has_position);
                    $is_short = $binance_position['positionAmt'] < 0 ? true : false;
                    // dump($is_short);
                } catch (\Exception $e) {
                    Log::channel('slack')->alert("Can't get position in Binance.", [
                        'asset' => $symbol,
                        'user' => $position->user->email,
                        'error' => $e->getMessage(),
                    ]);
                    continue;
                }

                // get active order for this position
                $order = $position->orders()->active()->first();

                if (!$order) {
                    // create order if no active order exists and binance position exists
                    if ($has_position) {
                        $order = $position->orders()->create([
                            'side' => $is_short ? 'sell' : 'buy',
                            'entry_price' => $binance_position['entryPrice'],
                            'quantity' => $binance_position['positionAmt'],
                            'size' => ($binance_position['positionAmt'] * $binance_position['entryPrice']) * -1,
                            'started_at' => now(),
                        ]);
                    }
                }

                if ($order->side != $request->action) {
                    // change order side
                    $log_message = 'Position order changed side';
                } else {
                    // maintain order side
                    $log_message = 'Maintain position order';
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
