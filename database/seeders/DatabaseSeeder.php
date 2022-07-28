<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Hilo;
use App\Models\Order;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $assets = Asset::factory(8)->create()->each(function ($asset) {
            Hilo::factory(1)->create(
                ['asset_id' => $asset->id]
            );
        });

        $users = User::factory(10)->create()->each(function ($user) use ($assets) {
            Position::factory(1)->create([
                'user_id' => $user->id,
                'asset_id' => $assets->random()->id,
            ]);
        });

        // Create my user
        $user = User::factory()->create([
            'name' => 'Ricardo Neto',
            'email' => 'ron2302@gmail.com',
            'password' => Hash::make('qwe123456')
        ]);

        Position::factory(1)->create([
            'user_id' => $user->id,
            'asset_id' => 1,
        ])->each(function ($position) {
            $position->update([
                'asset_id' => Asset::inRandomOrder()->first()->id
            ]);
            $position->orders()->saveMany(
                Order::factory(rand(1, 3))->make()
            );
        });

        dump($user->createToken('MyApp')->plainTextToken);
    }
}
