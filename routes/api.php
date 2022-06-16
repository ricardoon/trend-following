<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\HiloController;
use App\Http\Controllers\PositionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    // Assets
    Route::apiResource('assets', AssetController::class);
    Route::post('assets/bulk', 'App\Http\Controllers\AssetController@bulkStore');
    // Assets Hilos
    Route::get('hilos', 'App\Http\Controllers\HiloController@index');
    Route::get('hilos/{asset}', 'App\Http\Controllers\HiloController@show');
    Route::post('hilos', 'App\Http\Controllers\HiloController@store');
    Route::patch('hilos/{asset}', 'App\Http\Controllers\HiloController@update');

    // Positions
    Route::apiResource('positions', PositionController::class);
    // Position Orders
    Route::get('positions/{position}/orders', 'App\Http\Controllers\OrderController@index');
    Route::get('positions/{position}/orders/{external_id}', 'App\Http\Controllers\OrderController@show');
    Route::post('positions/{position}/orders', 'App\Http\Controllers\OrderController@store');
    Route::patch('positions/{position}/orders/{external_id}', 'App\Http\Controllers\OrderController@update');
    Route::delete('positions/{position}/orders/{external_id}', 'App\Http\Controllers\OrderController@destroy');
});
