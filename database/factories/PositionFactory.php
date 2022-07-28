<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PositionFactory extends Factory
{
    public function definition()
    {
        return [
            'exchange' => 'binance',
            'strategy' => 'hilo',
            'initial_amount' => rand(500, 99999),
            'leverage' => rand(1, 10),
            'granularity' => '1d',
            'max_stop' => rand(0, 10),
        ];
    }
}
