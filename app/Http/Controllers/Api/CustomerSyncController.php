<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerSyncRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerSyncController extends Controller
{
    public function sync(CustomerSyncRequest $request)
    {
        $data = $request->validate([
            'customers' => 'required|array',
            'customers.*.local_id' => 'required|integer',
            'customers.*.name' => 'required|string',
            'customers.*.phone_no' => 'nullable|string',
            'customers.*.address' => 'nullable|string',

            // ✅ FIXED map_location
            'customers.*.map_location' => 'nullable|array',
            'customers.*.map_location.lat' => 'required_with:customers.*.map_location|numeric|between:-90,90',
            'customers.*.map_location.lng' => 'required_with:customers.*.map_location|numeric|between:-180,180',

        ]);

        $result = [];

        foreach ($data['customers'] as $c) {
            $customer = Customer::updateOrCreate(
                [
                    'local_id' => $c['local_id'] // UNIQUE FIELD
                ],
                [
                    'phone_no' => $c['phone_no'],
                    'name' => $c['name'],
                    'address' => $c['address'],
                    'updated_at' => $c['updated_at'] ?? null,
                    'map_location' => $c['map_location'] ?? null
                ]
            );

            $result[] = [
                'local_id' => $c['local_id'],
                //'server_id' => $customer->id
            ];
        }

        return response()->json([
            'success' =>true,
            'synced' => $result
        ]);
//        foreach ($request->customers as $data) {
//            Customer::updateOrCreate(
//                ['local_id' => $data['local_id']],
//                $data
//            );
//        }
//
//        return response()->json([
//            'message' => 'Customers synced successfully'
//        ]);
    }

    public function syncMapLocation(Request $request)
    {
        $data = $request->validate([
            'local_id' => 'required|string',

            'map_location.lat' => 'required|numeric|between:-90,90',
            'map_location.lng' => 'required|numeric|between:-180,180',
        ]);

        $customer = Customer::where('local_id', $data['local_id'])->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        // ✅ Update ONLY map_location
        $customer->update([
            'map_location' => [
                'lat' => $data['map_location']['lat'],
                'lng' => $data['map_location']['lng'],
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Map location synced',
            'customer_id' => $customer->id
        ]);
    }
}
