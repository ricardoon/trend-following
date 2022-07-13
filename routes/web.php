<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/positions', \App\Http\Livewire\Positions\Listing::class)->name('positions');
    Route::get('/positions/create', \App\Http\Livewire\Positions\Create::class)->name('positions.create');
    Route::get('/positions/{position}', \App\Http\Livewire\Positions\Display::class)->name('positions.display');

    Route::get('/settings', function () {
        return view('dashboard');
    })->name('settings');

    Route::get('/api', function () {
        dump(Auth::user()->createToken('MyApp')->plainTextToken);
    })->name('settings');
});

require __DIR__ . '/auth.php';
