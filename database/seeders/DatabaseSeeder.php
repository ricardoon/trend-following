<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        \App\Models\User::factory(10)->create();

        $user = \App\Models\User::factory()->create([
            'name' => 'Ricardo Neto',
            'email' => 'ron2302@gmail.com',
            'password' => Hash::make('qwe123456')
        ]);

        dump($user->createToken('MyApp')->plainTextToken);

        \App\Models\Asset::factory(10)->create();
    }
}
