<?php

namespace App\Http\Livewire\Positions;

use App\Models\Asset;
use Livewire\Component;

class Create extends Component
{
    public function mount()
    {
        $this->assets = Asset::all();
    }

    public function render()
    {
        return view('livewire.positions.create')
            ->extends('layouts.app');
    }
}
