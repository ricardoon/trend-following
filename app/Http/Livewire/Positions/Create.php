<?php

namespace App\Http\Livewire\Positions;

use App\Models\Asset;
use App\Models\Position;
use Cknow\Money\Money;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    public $asset;
    public $strategy = 'hilo';
    public $granularity = '1d';
    public $amount;
    public $leverage;
    public $max_stop;

    protected function rules()
    {
        return [
            'asset' => [
                'required',
                Rule::in(Asset::pluck('id')->toArray()),
            ],
            'strategy' => [
                'required',
                Rule::in(array_keys(config('utils.strategies'))),
            ],
            'granularity' => [
                'required',
                Rule::in(config('utils.granularities')),
            ],
            'amount' => 'required',
            'leverage' => 'required|numeric|between:1,20',
            'max_stop' => 'nullable|numeric|between:0,100',
        ];
    }

    public function mount()
    {
        $this->assets = Asset::orderBy('name', 'ASC')->get();
    }

    public function render()
    {
        return view('livewire.positions.create')
            ->extends('layouts.app');
    }

    public function save()
    {
        $this->validate();

        $amount = str_replace('.', '', $this->amount);
        $amount = str_replace(',', '.', $amount);

        $position = Position::where('user_id', Auth::id())
            ->where('asset_id', $this->asset)
            ->where('strategy', $this->strategy)
            ->where('granularity', $this->granularity)
            ->active()
            ->first();

        if ($position) {
            session()->flash('flash.type', 'warning');
            session()->flash('flash.message', __('Position already exists.'));
            return redirect()->route('positions');
        }

        Position::create([
            'user_id' => Auth::id(),
            'asset_id' => $this->asset,
            'strategy' => $this->strategy,
            'granularity' => $this->granularity,
            'initial_amount' => $amount,
            'amount' => $amount,
            'leverage' => $this->leverage,
            'max_stop' => $this->max_stop,
        ]);

        session()->flash('flash.message', __('Position created successfully.'));

        return redirect()->route('positions');
    }
}
