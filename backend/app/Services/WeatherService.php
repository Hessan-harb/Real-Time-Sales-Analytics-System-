<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function getWeather()
    {
        $response = Http::get('http://api.openweathermap.org/data/2.5/weather', [
            'q' => 'Cairo,eg',
            'APPID' => env('WEATHER_API_KEY'),
        ]);
        //dd($response->json());
        if ($response->ok()) {
            $data = $response->json();
            return [
                'temperature' => isset($data['main']['temp']) ? $data['main']['temp'] - 273.15 : null,
                'condition' => $data['weather'][0]['description'] ?? 'Unknown',
            ];
        }
        return ['temperature' => null, 'condition' => 'Unknown'];
    }
}
