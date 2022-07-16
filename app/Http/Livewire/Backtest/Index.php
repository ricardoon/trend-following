<?php

namespace App\Http\Livewire\Backtest;

use Livewire\Component;

class Index extends Component
{
    public $yahoo_code;
    public $strategy = 'hilo';
    public $granularity = '1d';
    public $backtest_result;

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

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://elegant-monsieur-00286.herokuapp.com/best_window_report?asset='.$this->yahoo_code.'&start=01011900&end='.date('dmY'));
        $this->backtest_result = json_decode($response->getBody()->getContents());
    }
}
