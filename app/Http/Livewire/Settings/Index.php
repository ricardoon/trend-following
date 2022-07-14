<?php

namespace App\Http\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $settings;
    public $commissions;

    public function mount()
    {
        $this->settings = Auth::user()->settings;
        $this->commissions = null;
    }

    public function render()
    {
        return view('livewire.settings.index')
            ->extends('layouts.app');
    }
}
