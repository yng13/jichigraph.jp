<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenDataController;
use App\Http\Controllers\MunicipalitiesController;
use App\Http\Controllers\DatasetsController;

Route::middleware(['api'])->group(function () {
    // データ取得API
    Route::get('/open-data', [OpenDataController::class, 'index']);
    Route::get('/open-data/{id}', [OpenDataController::class, 'show']);
    Route::get('/datasets/{id}/open-data', [DatasetsController::class, 'getOpenData']);
    Route::get('/municipalities/{id}/open-data', [MunicipalitiesController::class, 'getOpenData']);

    // 自治体情報取得API
    Route::get('/municipalities', [MunicipalitiesController::class, 'index']);
    Route::get('/municipalities/{id}', [MunicipalitiesController::class, 'show']);

    // データセット情報取得API
    Route::get('/datasets', [DatasetsController::class, 'index']);
    Route::get('/datasets/{id}', [DatasetsController::class, 'show']);
});
