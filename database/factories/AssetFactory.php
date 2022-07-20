<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    private static $index = 0;

    public function definition()
    {
        $assets = [
            [
                'name' => 'Bitcoin',
                'code' => 'BTCUSDT',
                'yahoo_code' => 'BTC-USD',
                'image' => 'crypto/btc.png',
                'category' => 'crypto',
                'price_precision' => 2,
                'quantity_precision' => 2,
                'quote_precision' => 2,
            ],
            [
                'name' => 'Ethereum',
                'code' => 'ETHUSDT',
                'yahoo_code' => 'ETH-USD',
                'image' => 'crypto/eth.png',
                'category' => 'crypto',
                'price_precision' => 2,
                'quantity_precision' => 2,
                'quote_precision' => 2,
            ],
            [
                'name' => 'Cardano',
                'code' => 'ADAUSDT',
                'yahoo_code' => 'ADA-USD',
                'image' => 'crypto/ada.png',
                'category' => 'crypto',
                'price_precision' => 2,
                'quantity_precision' => 2,
                'quote_precision' => 2,
            ],
            [
                'name' => 'XRP',
                'code' => 'XRPUSDT',
                'yahoo_code' => 'XRP-USD',
                'image' => 'crypto/xrp.png',
                'category' => 'crypto',
                'price_precision' => 2,
                'quantity_precision' => 2,
                'quote_precision' => 2
            ],
            [
                'name' => 'AVAX',
                'code' => 'AVAXUSDT',
                'yahoo_code' => 'AVAX-USD',
                'image' => 'crypto/avax.png',
                'category' => 'crypto',
                'price_precision' => 2,
                'quantity_precision' => 2,
                'quote_precision' => 2,
            ],
            [
                'name' => 'FTM',
                'code' => 'FTMUSDT',
                'yahoo_code' => 'FTM-USD',
                'image' => 'crypto/ftm.png',
                'category' => 'crypto',
                'price_precision' => 2,
                'quantity_precision' => 2,
                'quote_precision' => 2,
            ],
            [
                'name' => 'Solana',
                'code' => 'SOLUSDT',
                'yahoo_code' => 'SOL-USD',
                'image' => 'crypto/sol.png',
                'category' => 'crypto',
                'price_precision' => 2,
                'quantity_precision' => 2,
                'quote_precision' => 2,
            ],
            [
                'name' => 'Litecoin',
                'code' => 'LTCUSDT',
                'yahoo_code' => 'LTC-USD',
                'image' => 'crypto/ltc.png',
                'category' => 'crypto',
                'price_precision' => 2,
                'quantity_precision' => 2,
                'quote_precision' => 2,
            ]
        ];

        return $assets[self::$index++];
    }
}
