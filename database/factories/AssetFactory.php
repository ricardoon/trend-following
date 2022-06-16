<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->firstName(),
            'code' => $this->faker->unique()->currencyCode(),
            'category' => $this->faker->randomElement(['crypto', 'option', 'stock']),
        ];
    }
}
