<?php

namespace App\Http\Controllers;

use App\Http\Requests\HiloNotifyRequest;
use App\Http\Requests\HiloRequest;
use App\Http\Resources\HiloResource;
use App\Libraries\Binance;
use App\Models\Asset;
use App\Models\Hilo;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Provider\Time\FixedTimeProvider;

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
        $quantity_precision = $hilo->asset->quantity_precision;

        // get active positions
        $positions = $hilo->asset->positions()->active()->get();

        if ($positions->count() === 0) {
            Log::info('No active positions for this asset and granularity.', [
                'asset' => $symbol,
                'action' => $request->action,
                'hilo' => $hilo,
            ]);
        } else {

            $a = 0;

            foreach ($positions as $position) {

                // $binance = new Binance(config('binance.api_key'), config('binance.api_secret'));
                $binance = new Binance(config('binance.test_api_key'), config('binance.test_api_secret'), 'https://testnet.binancefuture.com');

                try {
                    $binance_position = $binance->trade()->getPosition([
                        'symbol' => $symbol,
                    ])[0];
                    $has_position = $binance_position['positionAmt'] != 0 ? true : false;
                    $position_side = $binance_position['positionAmt'] < 0 ? 'sell' : ($has_position ? 'buy' : false);
                    $asset_price = $binance_position['markPrice'];
                } catch (\Exception $e) {
                    Log::channel('slack')->alert("Can't get position in Binance.", [
                        'asset' => $symbol,
                        'user' => $position->user->email,
                        'error' => $e->getMessage(),
                    ]);
                    continue;
                }

                if ($has_position) {
                    Log::info('Position found in binance.', [
                        'asset' => $symbol,
                        'user' => $position->user->email,
                        'binance_position' => $binance_position,
                    ]);

                    // mantain position side in binance
                    if ($position_side == $request->action) {
                        Log::info('Position ' . $request->action . ' already exists in binance.', [
                            'asset' => $symbol,
                            'user' => $position->user->email,
                            'action' => $request->action,
                            'position' => $position,
                        ]);
                        continue;
                    }

                    // close position on Binance
                    try {
                        $binance->trade()->postOrder([
                            'symbol' => $symbol,
                            'side' => strtoupper($request->action),
                            'type' => 'MARKET',
                            'quantity' => abs($binance_position['positionAmt']),
                            'reduceOnly' => true,
                        ]);
                    } catch (\Exception $e) {
                        Log::channel('slack')->alert("Can't close position on Binance.", [
                            'asset' => $symbol,
                            'user' => $position->user->email,
                            'error' => $e->getMessage(),
                        ]);
                        continue;
                    }
                    sleep(1);

                    // change position side
                    $log_message = 'Position order changed side from ' . $position_side . ' to ' . $request->action . '.';
                } else {
                    // check if max stop loss for position lets me create one now
                    $current_stop = (($request->hilo_price / $asset_price) - 1) * 100;
                    if ($position->max_stop < $current_stop) {
                        Log::info("Max stop not reached. Don't create position yet.", [
                            'asset' => $symbol,
                            'user' => $position->user->email,
                            'action' => $request->action,
                            'position' => $position,
                            'current_stop' => $current_stop,
                        ]);
                        continue;
                    }

                    // create first position
                    $log_message = 'Creating first position in binance.';
                }

                // get account balance
                try {
                    $binance_balance = $binance->account()->getBalance();
                    Log::info('Balance found in binance.', [
                        'user' => $position->user->email,
                        'binance_balance' => $binance_balance,
                    ]);
                } catch (\Exception $e) {
                    Log::channel('slack')->alert("Can't get balance from Binance.", [
                        'user' => $position->user->email,
                        'error' => $e->getMessage(),
                    ]);
                    continue;
                }

                $usdt_balance = 0;
                foreach ($binance_balance as $balance) {
                    if ($balance['asset'] == 'USDT') {
                        $usdt_balance = $balance['availableBalance'];
                        break;
                    }
                }

                $position_amount = $position->amount < $position->initial_amount ? $position->initial_amount : $position->amount;
                $position_amount = $position_amount <= $usdt_balance ? $position_amount : $usdt_balance;

                if ($position_amount == 0) {
                    Log::info('Position amount is 0. Don\'t create position.', [
                        'asset' => $symbol,
                        'user' => $position->user->email,
                        'usdt_balance' => $usdt_balance,
                    ]);
                    continue;
                }

                // remove 2% for margin
                $quantity = ($position_amount * 0.98) / $asset_price;
                $quantity = round($quantity * $position->leverage, $quantity_precision, PHP_ROUND_HALF_DOWN);

                Log::info($log_message, [
                    'asset' => $symbol,
                    'user' => $position->user->email,
                    'action' => $request->action,
                    'position' => $position,
                ]);

                // try to create position on Binance
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
                    $binance->trade()->postOrder([
                        'symbol' => $symbol,
                        'side' => strtoupper($request->action),
                        'type' => 'MARKET',
                        'quantity' => $quantity,
                    ]);
                } catch (\Exception $e) {
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
                    Log::channel('slack')->alert("Can't get position in Binance after its creation.", [
                        'asset' => $symbol,
                        'user' => $position->user->email,
                        'error' => $e->getMessage(),
                    ]);
                    continue;
                }

                if ($binance_position['positionAmt'] != 0) {
                    $log_message = 'Position created in binance.';
                    $position->update([
                        'amount' => $binance_position['isolatedMargin'],
                        'started_at' => $position->started_at ?? now(),
                    ]);
                } else {
                    $log_message = 'Position not created in binance.';
                }

                Log::info($log_message, [
                    'asset' => $symbol,
                    'user' => $position->user->email,
                    'action' => $request->action,
                    'binance_position' => $binance_position,
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
