<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition()
    {
        return [
            'position_id' => $this->faker->numberBetween(1, 10),
            'external_id' => $this->faker->uuid,
            'side' => $this->faker->randomElement(['buy', 'sell']),
            'entry_price' => $this->faker->randomFloat(2, 0, 100),
            'started_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'binance_client_order_id' => $this->faker->uuid,
        ];
    }
}
