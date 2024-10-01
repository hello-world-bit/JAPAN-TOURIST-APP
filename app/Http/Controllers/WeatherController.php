<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function getWeather($city)
    {
        $apiKey = '414462081ea0e3ee3afcd78b6615ceea';
        $url = "https://api.openweathermap.org/data/2.5/forecast?q={$city},JP&appid={$apiKey}";

        $response = Http::get($url);

        if ($response->failed()) {
            return response()->json(['error' => 'Error fetching weather data'], 500);
        }

        $weather = $response->json();

        // dd($weather);

        return response()->json($weather);
        
    }
    
}


