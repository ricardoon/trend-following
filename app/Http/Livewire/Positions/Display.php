<?php

namespace App\Http\Livewire\Positions;

use App\Libraries\Binance;
use App\Models\Position;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Display extends Component
{
    public $position;
    public $binance_position;

    public function mount($id)
    {
        $this->position = Position::findOrFail($id);

        $binance = new Binance(config('binance.test_api_key'), config('binance.test_api_secret'), 'https://testnet.binancefuture.com');

        if (!$this->binance_position = Cache::get('binance_position')) {
            try {
                $this->binance_position = $binance->trade()->getPosition([
                    'symbol' => $this->position->asset->code
                ])[0];
            } catch (\Exception $e) {
                $this->binance_position = null;
            }

            Cache::put('binance_position', $this->binance_position, 600);
        }
        // dd($this->binance_position);
    }

    public function render()
    {
        return view('livewire.positions.display')
            ->extends('layouts.app');
    }
}
