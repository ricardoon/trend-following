<?php

namespace App\Http\Livewire\Backtest;

use Livewire\Component;

class Index extends Component
{
    public $yahoo_code;
    public $strategy = 'hilo';
    public $granularity = '1d';

    public function rules()
    {
        return [
            'yahoo_code' => 'required|string|max:10',
            'strategy' => 'required|string|in:hilo',
            'granularity' => 'required|string|in:1d',
        ];
    }

    public function mount()
    {
        // $this->yahoo_code = 'AAPL';
    }

    public function render()
    {
        return view('livewire.backtest.index')
            ->extends('layouts.app');
    }

    public function run_backtest()
    {
        $this->validate();

        dd($this->yahoo_code);
    }
}
