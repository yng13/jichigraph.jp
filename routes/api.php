<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PointOfInterestController;
use App\Http\Controllers\PopulationStatisticController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\SolutionController;

// 追加

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// POIのAPIルート (既存)
Route::get('/pois', [PointOfInterestController::class, 'index']);
Route::get('/pois/{type}', [PointOfInterestController::class, 'indexByType']);

// 人口統計のAPIルート (既存)
Route::get('/population-statistics', [PopulationStatisticController::class, 'index']);
Route::get('/population-statistics/municipality/{municipalityCode}/region/{regionCode}', [PopulationStatisticController::class, 'showByMunicipalityAndRegion']);

// 課題投稿のAPIルート (既存)
Route::post('/issues', [IssueController::class, 'store']);
Route::get('/issues', [IssueController::class, 'index']); // 課題の一覧取得

// 新しく追加する解決策投稿のAPIルート
Route::post('/issues/{issue}/solutions', [SolutionController::class, 'store']); // 特定の課題に対する解決策の新規作成
Route::get('/issues/{issue}/solutions', [SolutionController::class, 'index']);  // 特定の課題に対する解決策の一覧取得
