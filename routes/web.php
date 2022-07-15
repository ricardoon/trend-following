<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', \App\Http\Livewire\Dashboard\Index::class)->name('dashboard');

    Route::get('/backtest', \App\Http\Livewire\Backtest\Index::class)->name('backtest');

    Route::get('/positions', \App\Http\Livewire\Positions\Listing::class)->name('positions');
    Route::get('/positions/create', \App\Http\Livewire\Positions\Create::class)->name('positions.create');
    Route::get('/positions/{id}', \App\Http\Livewire\Positions\Display::class)->name('positions.display');

    Route::get('/settings', \App\Http\Livewire\Settings\Index::class)->name('settings');

    Route::get('/api', function () {
        dump(Auth::user()->createToken('MyApp')->plainTextToken);
    })->name('api');
});

require __DIR__ . '/auth.php';
