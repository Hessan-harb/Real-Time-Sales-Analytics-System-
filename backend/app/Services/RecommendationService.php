<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Order;

class RecommendationService
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function getRecommendations()
    {
        $recentData = Order::latest()->take(10)->get();
        $recentDataFormatted = $recentData->map(function ($order) {
            return [
                'Order ID' => $order->id,
                'Product Name' => $order->product->name,
                'Quantity' => $order->quantity,
                'Price' => $order->price,
            ];
        });
        $weather = $this->weatherService->getWeather();
        $temperature = $weather['temperature']; 
        $weatherCondition = ($temperature >= 25) ? 'hot' : 'cold';
        $messages = [
            ['role' => 'system', 'content' => 'You are an assistant that provides clear and specific product promotions based on weather and recent sales data. Focus on the weather and relevant products.'],
            ['role' => 'user', 'content' => "Based on this sales data: " . json_encode($recentDataFormatted) . " and the weather condition being '$weatherCondition', provide concise and specific product promotion suggestions directly tied to the weather and products."],
        ];
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('AI_API_KEY'),
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',  // Use the correct model
            'messages' => $messages,
            'max_tokens' => 150,
        ]);
        //dd($response->json());
        $responseData = $response->json();
        if (isset($responseData['choices'][0]['message']['content'])) {
            return $responseData['choices'][0]['message']['content'];
        }
        return 'No recommendations available';
    }
}
