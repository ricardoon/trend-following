<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->firstName(),
            // 'code' => $this->faker->unique()->currencyCode(),
            'code' => $this->faker->unique()->randomElement(['BTCUSDT', 'ETHUSDT', 'BNBUSDT', 'XRPUSDT', 'EOSUSDT', 'LTCUSDT', 'BCHUSDT', 'FTMUSDT', 'ADAUSDT', 'SOLUSDT']),
            'category' => $this->faker->randomElement(['crypto', 'option', 'stock']),
            'precision' => $this->faker->numberBetween(3, 5),
        ];
    }
}
