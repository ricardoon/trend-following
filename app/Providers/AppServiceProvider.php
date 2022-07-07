<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('crypto', function ($json_expression) {
            $arr = json_decode($json_expression, true);
            $amount = $arr['amount'];
            $precision = $arr['precision'];
            return number_format($amount, $precision);
        });
    }
}
