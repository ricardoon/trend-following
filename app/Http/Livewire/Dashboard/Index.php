<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Index extends Component
{
    public $days;
    public $previousLastMonthStartDay;
    public $previousLastMonthEndDay;
    public $previousLastMonth;
    public $lastMonthStartDay;
    public $lastMonthEndDay;
    public $lastMonth;

    public function mount()
    {
        $this->setPreviousThirtyDays();
        $this->setLastMonthData();
        $this->setPreviousLastMonthData();
    }

    public function render()
    {
        return view('livewire.dashboard.index')
            ->extends('layouts.app');
    }

    protected function setPreviousThirtyDays()
    {
        $this->days = Collection::times(365, function ($index) {
            return __(now()->subDays($index)->format('m'));
        })->reverse()->values();
    }

    protected function setLastMonthData()
    {
        $this->lastMonthStartDay = now()->subDays(30)->startOfDay();
        $this->lastMonthEndDay = now()->subDays(1)->endOfDay();

        $orders = Order::whereBetween('created_at', [$this->lastMonthStartDay, $this->lastMonthEndDay])->get();

        $this->lastMonthAmount = $orders->sum(fn ($order) => $order->amount);
        $this->lastMonth = $this->days
            ->flip()
            ->map(fn () => 0)
            ->merge($this->groupOrdersByDay($orders))
            ->values()
            ->toArray();
    }

    protected function setPreviousLastMonthData()
    {
        $this->previousLastMonthStartDay = now()->subDays(60)->startOfDay();
        $this->previousLastMonthEndDay = now()->subDays(31)->endOfDay();

        $orders = Order::whereBetween('created_at', [$this->previousLastMonthStartDay, $this->previousLastMonthEndDay])->get();

        $this->previousLastMonthAmount = $orders->sum(fn ($order) => $order->amount);
        $this->previousLastMonth = $this->days
            ->flip()
            ->map(fn () => 0)
            ->merge($this->groupOrdersByDay($orders))
            ->values()
            ->toArray();
    }

    protected function groupOrdersByDay($orders)
    {
        return $orders->groupBy(fn ($order) => __($order->created_at->format('l')))->map(fn ($orders) => $orders->count());
    }
}
