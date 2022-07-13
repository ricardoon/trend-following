<?php

namespace App\Http\Livewire\Positions;

use App\Models\Position;
use Livewire\Component;

class Display extends Component
{
    public $position;

    public function mount($id)
    {
        $this->position = Position::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.positions.display')
            ->extends('layouts.app');
    }
}
