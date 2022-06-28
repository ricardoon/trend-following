<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        print(Auth::user()->createToken('MyApp')->plainTextToken);
        return view('dashboard');
    })->name('dashboard');

    Route::get('/positions', function () {
        return view('dashboard');
    })->name('positions');

    Route::get('/settings', function () {
        return view('dashboard');
    })->name('settings');
});

require __DIR__ . '/auth.php';
