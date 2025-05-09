<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index()
    {
        return view('weather');
    }

    public function getWeather(Request $request)
    {
        $lat = $request->input('latitude');
        $lon = $request->input('longitude');

        // 1. Fetch weather
        $weatherResponse = Http::get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => $lat,
            'longitude' => $lon,
            'current_weather' => true,
            'hourly' => 'precipitation_probability',
            'timezone' => 'auto'
        ]);

        $weatherData = $weatherResponse->json();

        // 2. Reverse geocode location
        $locationResponse = Http::withHeaders([
            'User-Agent' => 'LaravelWeatherApp/1.0 (your@email.com)'
        ])->get("https://nominatim.openstreetmap.org/reverse", [
            'lat' => $lat,
            'lon' => $lon,
            'format' => 'json'
        ]);

        $locationData = $locationResponse->json();

        \Log::info('🔍 Reverse geocode address:', $locationData['address'] ?? []);

        $city = $locationData['address']['city'] ??
                $locationData['address']['town'] ??
                $locationData['address']['village'] ??
                $locationData['address']['municipality'] ??
                $locationData['address']['county'] ??
                $locationData['address']['state'] ??
                $locationData['address']['country'] ??
                ($locationData['display_name'] ?? 'Unknown Location');

                $state = $locationData['address']['state'] ??
                $locationData['address']['region'] ??
                $locationData['address']['state_district'] ??
                null;       

        // 3. Match precipitation with current time
        $currentTime = $weatherData['current_weather']['time'];
        $index = array_search($currentTime, $weatherData['hourly']['time']);
        $rainChance = $weatherData['hourly']['precipitation_probability'][$index] ?? null;

        return response()->json([
            'weather' => $weatherData['current_weather'],
            'city' => $city,
            'state' => $state,
            'lat' => $lat,
            'lon' => $lon,
            'rain_chance' => $rainChance,
        ]);
    }
}
