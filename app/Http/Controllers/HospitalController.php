<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HospitalController extends Controller
{
    public function nearby(Request $request)
    {
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'type'      => 'required|string'
        ]);

        $lat  = $request->latitude;
        $lng  = $request->longitude;
        $type = $request->type;

        $radius = 3000;

        // Mapping kategori ke Overpass query
        $amenityMap = [
            'hospital'   => 'amenity=hospital',
            'restaurant' => 'amenity=restaurant',
            'cafe'       => 'amenity=cafe',
            'tourism'    => 'tourism=attraction',
            'supermarket' => 'shop=supermarket',
            'pharmacy'    => 'amenity=pharmacy',
            'fuel'        => 'amenity=fuel'
        ];

        if (!isset($amenityMap[$type])) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid type'
            ]);
        }

        [$key, $value] = explode('=', $amenityMap[$type]);

        $overpassQuery = "
        [out:json][timeout:25];
        (
          node[\"$key\"=\"$value\"](around:$radius,$lat,$lng);
          way[\"$key\"=\"$value\"](around:$radius,$lat,$lng);
          relation[\"$key\"=\"$value\"](around:$radius,$lat,$lng);
        );
        out center;
        ";

        $response = Http::timeout(60)
            ->withHeaders([
                'User-Agent' => 'Nearby-App/1.0'
            ])
            ->asForm()
            ->post(
                'https://overpass.kumi.systems/api/interpreter',
                ['data' => $overpassQuery]
            );

        if (!$response->successful()) {
            return response()->json([
                'status' => false,
                'error' => 'Overpass request failed'
            ]);
        }

        $elements = $response->json()['elements'] ?? [];

        $places = [];

        foreach ($elements as $element) {

            $placeLat = $element['lat'] ?? $element['center']['lat'] ?? null;
            $placeLng = $element['lon'] ?? $element['center']['lon'] ?? null;

            if (!$placeLat || !$placeLng) continue;

            $name = $element['tags']['name'] ?? null;
            if (!$name) continue;

            $distance = $this->haversine($lat, $lng, $placeLat, $placeLng);

            $places[] = [
                'name'        => $name,
                'type'        => $type,
                'address'     => $this->formatAddress($element['tags'] ?? []),
                'latitude'    => $placeLat,
                'longitude'   => $placeLng,
                'distance_km' => round($distance, 2),
            ];
        }

        $places = collect($places)
            ->unique('name')
            ->sortBy('distance_km')
            ->values()
            ->take(20)
            ->toArray();

        return response()->json([
            'status' => true,
            'total'  => count($places),
            'data'   => $places
        ]);
    }

    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    private function formatAddress($tags)
    {
        $parts = [
            $tags['addr:street'] ?? null,
            $tags['addr:housenumber'] ?? null,
            $tags['addr:city'] ?? null,
        ];

        return implode(', ', array_filter($parts));
    }
    public function route(Request $request)
{
    $request->validate([
        'user_lat' => 'required|numeric',
        'user_lng' => 'required|numeric',
        'hospital_lat' => 'required|numeric',
        'hospital_lng' => 'required|numeric',
    ]);

    $response = Http::withHeaders([
        'Authorization' => env('ORS_API_KEY'),
        'Content-Type' => 'application/json'
    ])->post(
        'https://api.openrouteservice.org/v2/directions/driving-car',
        [
            'coordinates' => [
                [$request->user_lng, $request->user_lat],
                [$request->hospital_lng, $request->hospital_lat]
            ]
        ]
    );

    $data = $response->json();

    if (!isset($data['routes'][0])) {
        return response()->json([
            'status' => false,
            'message' => 'Route not found'
        ]);
    }

    $route = $data['routes'][0];

    return response()->json([
        'status' => true,
        'distance_km' => round($route['summary']['distance'] / 1000, 2),
        'duration_min' => round($route['summary']['duration'] / 60, 1),
        'geometry' => $route['geometry']
    ]);
}
}