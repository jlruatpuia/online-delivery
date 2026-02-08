<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CustomerController extends Controller
{
    public function index() {
        $customers = Customer::get();
        return view('admin.customers.index', compact('customers'));
    }

    public function show(Customer $customer) {
//        $mapLocation = null;
//        if($customer->map_location){
//            $mapLocation = json_decode($customer->map_location, true);
//        }
        return view('admin.customers.show', compact('customer'));
    }

    public function geocode(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'address' => 'required|string',
        ]);

        $response = Http::get(
            'https://maps.googleapis.com/maps/api/geocode/json',
            [
                'address' => $request->address,
                'key' => config('services.google.geocode_key'),
            ]
        );

        $data = $response->json();

        if (($data['status'] ?? '') !== 'OK') {
            return response()->json([
                'success' => false,
                'message' => 'Unable to find location',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'lat' => $data['results'][0]['geometry']['location']['lat'],
            'lng' => $data['results'][0]['geometry']['location']['lng'],
        ]);
    }
    public function reverseGeocode(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $response = Http::get(
            'https://maps.googleapis.com/maps/api/geocode/json',
            [
                'latlng' => $request->lat . ',' . $request->lng,
                'key' => config('services.google.geocode_key'),
            ]
        );

        $data = $response->json();

        if (($data['status'] ?? '') !== 'OK') {
            return response()->json([
                'success' => false,
                'message' => 'Unable to reverse geocode location',
            ], 422);
        }

        $components = $data['results'][0]['address_components'];

        $map = fn ($type) =>
            collect($components)
                ->firstWhere(fn ($c) => in_array($type, $c['types']))
            ['long_name'] ?? null;

        return response()->json([
            'success' => true,
            'address' => [
                'house_no'     => $map('street_number'),
                'area_village' => $map('route') ?? $map('sublocality'),
                'landmark'     => $map('point_of_interest'),
                'town_city'    => $map('locality'),
                'state'        => $map('administrative_area_level_1'),
                'pin'          => $map('postal_code'),
                'region'       => $map('administrative_area_level_2'),
            ]
        ]);
    }
}
