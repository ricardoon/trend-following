<?php

namespace App\Http\Livewire\Settings;

use App\Libraries\Binance;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $settings;
    public $commissions;
    public $binance_api_key;
    public $binance_api_secret;

    protected function rules()
    {
        return [
            'binance_api_key' => 'required|string|min:40',
            'binance_api_secret' => 'required|string|min:40',
        ];
    }

    public function mount()
    {
        $this->settings = Auth::user()->settings;
        $this->commissions = Auth::user()->commissions;
    }

    public function render()
    {
        return view('livewire.settings.index')
            ->extends('layouts.app');
    }

    public function save_binance()
    {
        $this->validate();

        // Test the API key and secret before saving.
        try {
            $binance = new Binance($this->binance_api_key, $this->binance_api_secret);
            $binance->account()->getInfo();
        } catch (\Exception $e) {
            session()->flash('flash.type', 'warning');
            session()->flash('flash.message', __('Invalid Binance API credentials.'));
            return redirect()->route('settings');
        }

        $settings = Auth::user()->settings()->first();
        $data = [
            'binance' => [
                'api_key' => $this->binance_api_key,
                'api_secret' => $this->binance_api_secret,
            ],
        ];

        if ($settings) {
            $settings->update($data);
        } else {
            Auth::user()->settings()->create($data);
        }

        session()->flash('flash.message', __('Binance configured successfully.'));

        return redirect()->route('settings');
    }
}
