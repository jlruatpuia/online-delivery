<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeocodeController extends Controller
{
        public function geocode(Request $request)
    {
        // 1️⃣ Validate input (structured address)
        $data = $request->validate([
            'house_no'      => 'nullable|string|max:100',
            'area_village'  => 'nullable|string|max:150',
            'landmark'      => 'nullable|string|max:150',
            'town_city'     => 'nullable|string|max:100',
            'state'         => 'nullable|string|max:100',
            'pin'           => 'nullable|string|max:10',
            'region'        => 'nullable|string|max:100',
        ]);

        // 2️⃣ Enforce minimum address quality
        if (empty($data['town_city']) && empty($data['pin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Town/City or PIN is required for geocoding.'
            ], 422);
        }

        // 3️⃣ Build a strong address string (order matters)
        $addressParts = [
            $data['house_no']     ?? null,
            $data['area_village'] ?? null,
            $data['landmark']     ?? null,
            $data['town_city']    ?? null,
            $data['pin']          ?? null,
            $data['state']        ?? null,
            'India',
        ];

        $address = collect($addressParts)
            ->filter(fn ($v) => !empty(trim($v)))
            ->implode(', ');

        // 4️⃣ Call Google Geocoding API
        $response = Http::withoutVerifying()->get(
            'https://maps.googleapis.com/maps/api/geocode/json',
            [
                'address' => $address,
                'key'     => config('services.google.maps_key'),
            ]
        );

        if (!$response->ok()) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to connect to Geocoding service.'
            ], 500);
        }

        $json = $response->json();

        if (($json['status'] ?? '') !== 'OK' || empty($json['results'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to geocode address.',
                'google_status' => $json['status'] ?? null,
            ], 422);
        }

        // 5️⃣ Pick the MOST specific result (street/locality > others)
        $results = collect($json['results']);

        $best = $results->first(function ($r) {
            return in_array('street_address', $r['types'])
                || in_array('premise', $r['types'])
                || in_array('locality', $r['types']);
        }) ?? $results->first();

        // 6️⃣ Reject country-only results (e.g. "India")
        if (
            count($best['address_components']) === 1 &&
            in_array('country', $best['address_components'][0]['types'])
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Address too broad. Please provide city or PIN.'
            ], 422);
        }

        // 7️⃣ Extract final data
        $location = $best['geometry']['location'];
        $accuracy = $best['geometry']['location_type'] ?? 'UNKNOWN';

        return response()->json([
            'success' => true,
            'address' => $best['formatted_address'],
            'lat'     => $location['lat'],
            'lng'     => $location['lng'],
            'accuracy'=> $accuracy, // ROOFTOP / RANGE_INTERPOLATED / APPROXIMATE
        ]);
    }

}
