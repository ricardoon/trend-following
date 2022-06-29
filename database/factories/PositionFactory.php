<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PositionFactory extends Factory
{
    public function definition()
    {
        return [
            'strategy' => 'hilo',
            'amount' => rand(500, 99999),
            'granularity' => '1d',
            'max_stop' => rand(0, 10),
        ];
    }
}
