<?php

namespace App\Http\Livewire\Positions;

use App\Models\Position;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Listing extends Component
{
    public function mount()
    {
        $this->positions = Auth::user()->positions;
    }

    public function render()
    {
        return view('livewire.positions.listing')
            ->extends('layouts.app');
    }
}
