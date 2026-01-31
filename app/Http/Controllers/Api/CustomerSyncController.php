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
        foreach ($request->customers as $data) {
            Customer::updateOrCreate(
                ['local_id' => $data['local_id']],
                $data
            );
        }

        return response()->json([
            'message' => 'Customers synced successfully'
        ]);
    }
}
