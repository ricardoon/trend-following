<?php

namespace App\Http\Livewire\Positions;

use App\Libraries\Binance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Display extends Component
{
    public $position;
    public $binance_position = null;
    public $binance_orders = [];

    public function mount($id)
    {
        $this->position = Auth::user()->positions()->findOrFail($id);

        // TODO: Save position info in our database instead of fetching it from Binance.
        if ($this->position['ended_at'] != null) {
            session()->flash('flash.type', 'warning');
            session()->flash('flash.message', __('The position is closed.'));
            return redirect()->route('positions.index');
        }

        try {
            // $binance = new Binance(Auth::user()->settings->binance['api_key'], Auth::user()->settings->binance['api_secret']);
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

                    Cache::put('binance_position_' . $this->position->asset->code, $this->binance_position, 60);
                } catch (\Exception $e) {
                    $this->binance_position = null;
                    Log::info('Can\'t get position from Binance.', [
                            'route' => 'positions.display',
                            'position' => $this->position,
                            'error' => $e->getMessage()
                        ]);
                }
            }

            dd($this->binance_position);

            if (!$this->binance_orders = Cache::get('binance_orders_' . $this->position->asset->code)) {
                try {
                    $this->binance_orders = $binance->trade()->getOrders([
                        'symbol' => $this->position->asset->code,
                        'limit' => 10
                    ]);
                    $id = array_column($this->binance_orders, 'orderId');
                    array_multisort($id, SORT_DESC, $this->binance_orders);

                    Cache::put('binance_order_' . $this->position->asset->code, $this->binance_orders, 60);
                } catch (\Exception $e) {
                    $this->binance_orders = [];
                    Log::info('Can\'t get position orders from Binance.', [
                        'route' => 'positions.display',
                        'position' => $this->position,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        } catch (\Exception $e) {
            session()->flash('flash.type', 'error');
            session()->flash('flash.message', __('Could not connect to Binance.'));
            Log::info('Can\'t connect to Binance.', [
                'route' => 'positions.display',
                'position' => $this->position,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.positions.display')
            ->extends('layouts.app');
    }

    public function close()
    {
        try {
            // close Binance position if it exists
            if ($this->binance_position && $this->binance_position['positionAmt'] != 0) {
                // $binance = new Binance(Auth::user()->settings->binance['api_key'], Auth::user()->settings->binance['api_secret']);
                $binance = new Binance(config('binance.test_api_key'), config('binance.test_api_secret'), 'https://testnet.binancefuture.com');

                $binance->trade()->postOrder([
                    'symbol' => $this->position->asset->code,
                    'side' => $this->binance_position['side'] == 'long' ? 'SELL' : 'BUY',
                    'type' => 'MARKET',
                    'quantity' => $this->binance_position['positionAmt'],
                    'reduceOnly' => true,
                ]);
            }

            $this->position->ended_at = now();
            $this->position->save();

            session()->flash('flash.type', 'success');
            session()->flash('flash.message', __('Position closed successfully.'));
        } catch (\Exception $e) {
            session()->flash('flash.type', 'error');
            session()->flash('flash.message', __('Could not connect to Binance. Check your credentials and try again.'));
            Log::info('Can\'t connect to Binance.', [
                'route' => 'positions.display',
                'position' => $this->position,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->route('positions');
    }
}
