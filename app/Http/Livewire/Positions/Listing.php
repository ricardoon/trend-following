<?php

namespace App\Http\Livewire\Positions;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Cknow\Money\Money;

class Listing extends Component
{
    public function mount()
    {
        $this->positions = Auth::user()->positions()->active()->get();
    }

    public function render()
    {
        return view('livewire.positions.listing')
            ->extends('layouts.app');
    }
}
