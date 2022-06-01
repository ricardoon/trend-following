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

    Route::apiResource('hilos', HiloController::class);

    // Positions
    Route::apiResource('positions', PositionController::class);
});
