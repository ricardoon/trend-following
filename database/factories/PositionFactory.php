<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PositionFactory extends Factory
{
    public function definition()
    {
        return [
            'strategy' => 'hilo',
        ];
    }
}
