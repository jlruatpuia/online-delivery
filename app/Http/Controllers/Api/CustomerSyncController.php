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
        $request->validate([
            'customers' => 'required|array'
        ]);

        $result = [];

        foreach ($request->customers as $c) {
            $customer = Customer::updateOrCreate(
                [
                    'local_id' => $c['local_id'] // UNIQUE FIELD
                ],
                [
                    'phone_no' => $c['phone_no'],
                    'name' => $c['name'],
                    'address' => $c['address'],
                    'map_location' => $c['map_location'],
                    'updated_at' => $c['updated_at']
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
}
