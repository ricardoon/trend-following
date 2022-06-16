<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HiloFactory extends Factory
{
    public function definition()
    {
        return [
            'length' => $this->faker->numberBetween(1, 60),
            'last_check_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
