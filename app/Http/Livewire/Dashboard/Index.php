<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Index extends Component
{
    public $days;
    public $lastYearStartDay;
    public $lastYearEndDay;
    public $lastYear;

    public function mount()
    {
        $this->setPreviousTwelveMonths();
        $this->setLastYearData();
    }

    public function render()
    {
        return view('livewire.dashboard.index')
            ->extends('layouts.app');
    }

    protected function setPreviousTwelveMonths()
    {
        $this->months = Collection::times(12, function ($index) {
            return __(now()->subMonths($index)->format('M/y'));
        })->reverse()->values();
    }

    protected function setLastYearData()
    {
        $this->lastYearStartDay = now()->subMonths(12)->startOfDay();
        $this->lastYearEndDay = now()->subDays(1)->endOfDay();

        $orders = Order::whereBetween('created_at', [$this->lastYearStartDay, $this->lastYearEndDay])->get();

        $this->lastYearAmount = $orders->sum(fn ($order) => $order->size);
        $this->lastYear = $this->months
            ->flip()
            ->map(fn () => 0)
            ->merge($this->groupOrdersByDay($orders))
            ->values()
            ->toArray();
    }

    protected function groupOrdersByDay($orders)
    {
        return $orders->groupBy(fn ($order) => __($order->created_at->format('M/y')))->map(fn ($orders) => $orders->sum(fn ($order) => $order->size));
    }
}
