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
<<<<<<< HEAD

        $response = Http::get($url);

=======
    
        $response = Http::get($url);

        // Check for errors
>>>>>>> development
        if ($response->failed()) {
            return response()->json(['error' => 'Error fetching weather data'], 500);
        }

        $weather = $response->json();

<<<<<<< HEAD
        // dd($weather);

        return response()->json($weather);
        
=======
        return response()->json($weather);

>>>>>>> development
    }
    
}


