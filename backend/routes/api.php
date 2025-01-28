<?php

use App\Http\Controllers\API\SalesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('createOrder',[SalesController::class,'createOrder']);
Route::get('get',[SalesController::class,'getAnalytics']);
Route::get('recom',[SalesController::class,'recommendations']);
Route::get('weather',[SalesController::class,'weather']);
Route::get('/latest-order', [SalesController::class, 'getLatestOrder']);

