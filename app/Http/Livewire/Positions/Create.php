<?php

namespace App\Http\Livewire\Positions;

use App\Models\Asset;
use Livewire\Component;

class Create extends Component
{
    public $asset;
    public $strategy = 'hilo';
    public $granularity = '1d';
    public $amount = '0,00';
    public $leverage = '3';

    public function mount()
    {
        $this->assets = Asset::orderBy('name', 'ASC')->get();
    }

    public function render()
    {
        return view('livewire.positions.create')
            ->extends('layouts.app');
    }
}
