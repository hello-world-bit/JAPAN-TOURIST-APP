<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PlaceController extends Controller
{
    public function getPlaces($city, $limit = 50, $offset = 0)
    {        
        $apiKey = 'fsq3CO+2T6oh2hk2g87oQhWpyhKEJrv9gSWMfxRjyb7XQ84='; 
        $url = "https://api.foursquare.com/v3/places/search?near=" . urlencode($city) . "&limit=" . $limit . "&offset=" . $offset;

        $response = Http::withHeaders([
            'Authorization' => $apiKey,
            'Accept' => 'application/json',
        ])->get($url);

        if ($response->failed()) {
            return response()->json(['error' => 'Error fetching places data'], 500);
        }

        $places = $response->json();

        // dd($places);

        return response()->json($places);
    }

}
