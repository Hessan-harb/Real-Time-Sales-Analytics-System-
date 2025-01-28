<?php

use App\Events\OrderCreated;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test-event', function () {
    $order = App\Models\Order::first();
    $analytics = [
        'total_revenue' => 1000,
        'recent_revenue' => 200,
        'order_count' => 10,
    ];

    broadcast(new App\Events\OrderCreated($order, $analytics))->toOthers();

    return [
        'message' => 'Event broadcasted successfully',
        'order'=>$order,
        'analytics'=>$analytics
    ];
});
