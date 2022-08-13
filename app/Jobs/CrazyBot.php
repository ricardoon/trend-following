<?php

namespace App\Jobs;

use App\Libraries\Binance;
use App\Models\Asset;
use App\Models\Hilo;
use App\Models\Position;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CrazyBot implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $positions;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->positions = Position::whereIn('user_id', [11, 13])->active()->get();
        // Only get bitcoin positions for now
        $this->positions = Position::whereIn('user_id', [11, 13])->where('asset_id', 1)->active()->get();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Config for bitcoin
        $stop_loss = 0.001;
        $take_profit = 0.005;
        $leverage = 50;

        if ($this->positions->count() === 0) {
            Log::info('No active positions found.', [
                'schedule' => 'CrazyBot',
            ]);
        } else {
            $asset_ids = array_unique($this->positions->pluck('asset_id')->toArray());
            $db_assets = Asset::whereIn('id', $asset_ids)->get();
            $assets = [];
            foreach ($db_assets as $db_asset) {
                $hilo = Hilo::where('asset_id', $db_asset->id)->where('granularity', '1d')->first();
                $assets[$db_asset->id] = [
                    'code' => $db_asset->code,
                    'action' => $hilo->last_action ?? 'buy',
                    'quantity_precision' => $db_asset->quantity_precision,
                    'price_precision' => $db_asset->price_precision,
                ];
            }

            foreach ($this->positions as $position) {
                $binance = new Binance($position->user->settings->binance['api_key'], $position->user->settings->binance['api_secret']);
                // $binance = new Binance(config('binance.test_api_key'), config('binance.test_api_secret'), 'https://testnet.binancefuture.com');
                $symbol = $assets[$position->asset_id]['code'];
                $action = $assets[$position->asset_id]['action'];
                $quantity_precision = $assets[$position->asset_id]['quantity_precision'];
                $price_precision = $assets[$position->asset_id]['price_precision'];
                if ($position->user->id == 11) {
                    $leverage = 50;
                } else {
                    $leverage = 20;
                }

                try {
                    $binance_position = $binance->trade()->getPosition([
                        'symbol' => $symbol,
                    ])[0];
                    $has_position = $binance_position['positionAmt'] != 0 ? true : false;
                    $position_side = $binance_position['positionAmt'] < 0 ? 'sell' : ($has_position ? 'buy' : false);
                    $asset_price = $binance_position['markPrice'];
                } catch (\Exception $e) {
                    Log::channel('slack')->alert("Can't get position in Binance.", [
                        'schedule' => 'CrazyBot',
                        'asset' => $symbol,
                        'user' => $position->user->email,
                        'error' => $e->getMessage(),
                    ]);
                    continue;
                }

                if ($has_position) {
                    Log::info('Position found in binance.', [
                        'schedule' => 'CrazyBot',
                        'asset' => $symbol,
                        'user' => $position->user->email,
                        'binance_position' => $binance_position,
                    ]);

                    $position->update([
                        'amount' => $binance_position['isolatedMargin']
                    ]);

                    // Notify Profits/Losses
                    $profit = $binance_position['isolatedMargin'] - 95;
                    if ($profit != 0 && date('i') == '20') { // every hour
                        Log::channel('slack')->info(($profit > 0 ? 'Parabéns' : 'Que pena').", ".$position->user->name." você está ".money($profit)." mais ".($profit > 0 ? 'rico' : 'pobre')." do que quando começou.", [
                            'schedule' => 'CrazyBot',
                            'asset' => $symbol,
                            'user' => $position->user->email,
                        ]);
                    }

                // dump($binance_position, $position_side, $asset_price);
                } else {
                    // get account balance
                    try {
                        $binance_balance = $binance->account()->getBalance();
                        Log::info('Balance found in binance.', [
                            'schedule' => 'CrazyBot',
                            'user' => $position->user->email,
                            'binance_balance' => $binance_balance,
                        ]);
                    } catch (\Exception $e) {
                        Log::channel('slack')->alert("Can't get balance from Binance.", [
                            'schedule' => 'CrazyBot',
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

                    $position_amount = $position->amount != null ? $position->amount : $position->initial_amount;
                    $position_amount = $position_amount <= $usdt_balance ? $position_amount : $usdt_balance;
                    // dump('position_amount', $position_amount);

                    if ($position_amount <= 55) {
                        Log::info('Position amount is 0. Don\'t create position.', [
                            'schedule' => 'CrazyBot',
                            'asset' => $symbol,
                            'user' => $position->user->email,
                            'usdt_balance' => $usdt_balance,
                        ]);
                        continue;
                    }

                    // remove 2% for margin
                    $quantity = ($position_amount * 0.98) / $asset_price;
                    $quantity = round($quantity * $leverage, $quantity_precision, PHP_ROUND_HALF_DOWN);

                    // Make sure margin type is ISOLATED
                    try {
                        $binance->trade()->postMarginType([
                            'symbol' => $symbol,
                            'marginType' => 'ISOLATED',
                        ]);
                    } catch (\Exception $e) {
                    }

                    // try to create position on Binance
                    try {
                        // Cancel all open orders
                        $binance->trade()->cancelAllOpenOrders([
                            'symbol' => $symbol,
                        ]);
                        // Make sure leverage is set to position leverage
                        $binance->trade()->postLeverage([
                            'symbol' => $symbol,
                            'leverage' => $leverage,
                        ]);
                        // My position
                        $binance->trade()->postOrder([
                            'symbol' => $symbol,
                            'side' => strtoupper($action),
                            'type' => 'MARKET',
                            'quantity' => $quantity,
                        ]);
                    } catch (\Exception $e) {
                        Log::channel('slack')->alert("Can't create order on Binance.", [
                            'schedule' => 'CrazyBot',
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
                            'schedule' => 'CrazyBot',
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
                        'schedule' => 'CrazyBot',
                        'asset' => $symbol,
                        'user' => $position->user->email,
                        'action' => $action,
                        'binance_position' => $binance_position,
                    ]);

                    $asset_price = $binance_position['entryPrice'];

                    $stop_loss_price = $action == 'buy' ? $asset_price * (1 - $stop_loss) : $asset_price * (1 + $stop_loss);
                    // dump('stop_loss', $asset_price, $stop_loss, 1 - $stop_loss, $stop_loss_price);
                    $stop_loss_price = round($stop_loss_price, $price_precision, PHP_ROUND_HALF_DOWN);
                    $take_profit_price = $action == 'buy' ? $asset_price * (1 + $take_profit) : $asset_price * (1 - $take_profit);
                    // dump('take_profit', $asset_price, $take_profit, 1 + $take_profit, $take_profit_price);
                    $take_profit_price = round($take_profit_price, $price_precision, PHP_ROUND_HALF_DOWN);
                    $stop_action = $action == 'buy' ? 'sell' : 'buy';

                    try {
                        // My stop loss
                        $binance->trade()->postOrder([
                            'symbol' => $symbol,
                            'side' => strtoupper($stop_action),
                            'type' => 'STOP_MARKET',
                            'stopPrice' => $stop_loss_price,
                            'quantity' => $quantity,
                            'reduceOnly' => true,
                        ]);
                        // My take profit
                        $binance->trade()->postOrder([
                            'symbol' => $symbol,
                            'side' => strtoupper($stop_action),
                            'type' => 'TAKE_PROFIT_MARKET',
                            'stopPrice' => $take_profit_price,
                            'quantity' => $quantity,
                            'reduceOnly' => true,
                        ]);
                    } catch (\Exception $e) {
                        Log::channel('slack')->alert("Can't create order on Binance.", [
                            'schedule' => 'CrazyBot',
                            'asset' => $symbol,
                            'user' => $position->user->email,
                            'error' => $e->getMessage(),
                        ]);
                        continue;
                    }
                }
            }
        }
    }
}
