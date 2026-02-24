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
        ]);

        $lat = $request->latitude;
        $lng = $request->longitude;

        $radius = 3000; // 10km

        $overpassQuery = "
        [out:json][timeout:25];
        (
          node[\"amenity\"=\"hospital\"](around:$radius,$lat,$lng);
          way[\"amenity\"=\"hospital\"](around:$radius,$lat,$lng);
          relation[\"amenity\"=\"hospital\"](around:$radius,$lat,$lng);
        );
        out center;
        ";

        $response = Http::timeout(60)
        ->withHeaders([
            'User-Agent' => 'RS-Terdekat-App/1.0'
        ])
        ->asForm()
        ->post(
            'https://overpass.kumi.systems/api/interpreter',
            ['data' => $overpassQuery]
        );

        if (!$response->successful()) {
            return response()->json([
                'status' => false,
                'error' => 'Overpass request failed',
                'response_status' => $response->status(),
                'body' => $response->body()
            ]);
        }

        $elements = $response->json()['elements'] ?? [];

        $hospitals = [];

        foreach ($elements as $element) {

            $hospitalLat = $element['lat'] ?? $element['center']['lat'] ?? null;
            $hospitalLng = $element['lon'] ?? $element['center']['lon'] ?? null;

            if (!$hospitalLat || !$hospitalLng) {
                continue;
            }

            $name = $element['tags']['name'] ?? null;

            if (!$name) {
                continue;
            }

            $distance = $this->haversine($lat, $lng, $hospitalLat, $hospitalLng);

            $hospitals[] = [
                'name'        => $name,
                'address'     => $this->formatAddress($element['tags'] ?? []),
                'latitude'    => $hospitalLat,
                'longitude'   => $hospitalLng,
                'distance_km' => round($distance, 2),
            ];
        }

        // 🔥 Hapus duplikat berdasarkan nama
        $hospitals = collect($hospitals)
            ->unique('name')
            ->values()
            ->toArray();

        // 🔥 Urutkan dari yang paling dekat
        usort($hospitals, function ($a, $b) {
            return $a['distance_km'] <=> $b['distance_km'];
        });

        // 🔥 Ambil 20 terdekat saja
        $hospitals = array_slice($hospitals, 0, 20);

        return response()->json([
            'status' => true,
            'user_location' => [
                'latitude' => $lat,
                'longitude' => $lng
            ],
            'total' => count($hospitals),
            'data' => $hospitals
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