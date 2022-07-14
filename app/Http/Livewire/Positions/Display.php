<?php

namespace App\Http\Livewire\Positions;

use App\Libraries\Binance;
use App\Models\Position;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Display extends Component
{
    public Position $position;
    public $binance_position;
    public $binance_orders;

    public function mount()
    {
        $binance = new Binance(config('binance.test_api_key'), config('binance.test_api_secret'), 'https://testnet.binancefuture.com');

        if (!$this->binance_position = Cache::get('binance_position_' . $this->position->asset->code)) {
            try {
                $this->binance_position = $binance->trade()->getPosition([
                    'symbol' => $this->position->asset->code
                ])[0];
                // dont have a position yet
                if ($this->binance_position['positionAmt'] == 0) {
                    $this->binance_position = null;
                } else {
                    $this->binance_position['side'] = $this->binance_position['positionAmt'] > 0 ? 'long' : 'short';
                    $this->binance_position['result'] = round(($this->binance_position['unRealizedProfit'] / $this->binance_position['isolatedWallet']) * 100, 0, PHP_ROUND_HALF_UP);
                }
            } catch (\Exception $e) {
                $this->binance_position = null;
            }

            Cache::put('binance_position_' . $this->position->asset->code, $this->binance_position, 600);
        }

        if (!$this->binance_orders = Cache::get('binance_orders_' . $this->position->asset->code)) {
            try {
                $this->binance_orders = $binance->trade()->getOrders([
                    'symbol' => $this->position->asset->code,
                    'limit' => 10
                ]);
                $id = array_column($this->binance_orders, 'orderId');
                array_multisort($id, SORT_DESC, $this->binance_orders);
            } catch (\Exception $e) {
                $this->binance_orders = null;
            }

            Cache::put('binance_order_' . $this->position->asset->code, $this->binance_orders, 600);
        }
        // dd($this->binance_position);
        // dd($this->binance_orders);
    }

    public function render()
    {
        return view('livewire.positions.display')
            ->extends('layouts.app');
    }
}