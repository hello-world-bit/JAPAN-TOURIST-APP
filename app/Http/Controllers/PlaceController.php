<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function getPlaces($city, $limit = 50, $offset = 0)
    {        
        $apiKey = 'fsq3CO+2T6oh2hk2g87oQhWpyhKEJrv9gSWMfxRjyb7XQ84='; 
        $url = "https://api.foursquare.com/v3/places/search?near=" . urlencode($city) . "&limit=" . $limit . "&offset=" . $offset;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: ' . $apiKey,
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return response()->json(['error' => curl_error($ch)], 500);
        }

        curl_close($ch);

        $places = json_decode($response);

        // dd($places);

        return response()->json($places);
    }

}
