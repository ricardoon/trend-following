<?php

namespace App\Http\Livewire\Backtest;

use Livewire\Component;

class Index extends Component
{
    public $yahoo_code;
    public $strategy = 'hilo';
    public $granularity = '1d';
    public $backtest_result;
    public $start_date;
    public $end_date;

    public function rules()
    {
        return [
            'yahoo_code' => 'required|string|max:10',
            'strategy' => 'required|string|in:hilo',
            'granularity' => 'required|string|in:1d',
        ];
    }

    public function render()
    {
        return view('livewire.backtest.index')
            ->extends('layouts.app');
    }

    public function run_backtest()
    {
        $this->validate();

        $this->start_date ??= '01011900';
        $this->end_date ??= date('dmY');

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', 'https://elegant-monsieur-00286.herokuapp.com/best_window_report?asset='.$this->yahoo_code.'&start='.$this->start_date.'&end='.$this->end_date.'&strategy='.$this->strategy.'&granularity='.$this->granularity);
            $this->backtest_result = json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            $this->backtest_result = null;
        }
    }
}
