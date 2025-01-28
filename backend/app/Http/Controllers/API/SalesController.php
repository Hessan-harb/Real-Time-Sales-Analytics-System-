<?php

namespace App\Http\Controllers\API;

use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRquest;
use App\Models\Order;
use App\Services\WeatherService;
use App\Services\RecommendationService;
use App\Services\AnalyticsService;

class SalesController extends Controller
{
    protected $weatherService;
    protected $recommendationService;
    protected $analyticsService;

    public function __construct(WeatherService $weatherService,RecommendationService $recommendationService,AnalyticsService $analyticsService) 
    {
        $this->weatherService = $weatherService;
        $this->recommendationService = $recommendationService;
        $this->analyticsService = $analyticsService;
    }
    public function getLatestOrder()
    {
        $order = Order::with('product')->latest()->first();
        return response()->json($order);
    }
    public function createOrder(OrderRquest $request)
    {
        $validated = $request->validated();
        $order = Order::create($validated);
        $analytics = $this->analyticsService->getAnalytics();
        //OrderCreated::dispatch($order, $analytics);
        //broadcast(new OrderCreated($order, $analytics))->via('pusher')->toOthers();
        event(new OrderCreated($order, $analytics));
        return response()->json($order, 201);
    }
    public function getAnalytics()
    {
        $analytics = $this->analyticsService->getAnalytics(); 
        return response()->json($analytics);
    }

    public function recommendations()
    {
        $recommendations = $this->recommendationService->getRecommendations();
        return response()->json(['recommendations' => $recommendations]);
    }

    public function weather()
    {
        return $this->weatherService->getWeather();
    }
}
