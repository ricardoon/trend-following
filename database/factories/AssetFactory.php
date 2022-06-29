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
            'yahoo_code' => $this->faker->unique()->randomElement(['BTC-USD', 'ETH-USD', 'BNB-USD', 'XRP-USD', 'EOS-USD', 'LTC-USD', 'BCH-USD', 'FTM-USD', 'ADA-USD', 'SOL-USD']),
            'category' => $this->faker->randomElement(['crypto', 'option', 'stock']),
            'price_precision' => $this->faker->numberBetween(3, 5),
            'quantity_precision' => $this->faker->numberBetween(3, 5),
            'quote_precision' => $this->faker->numberBetween(3, 5),
        ];
    }
}
