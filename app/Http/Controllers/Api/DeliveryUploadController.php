<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryUploadRequest;
use App\Models\Customer;
use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveryUploadController extends Controller
{
    public function upload(DeliveryUploadRequest $request)
    {
        foreach ($request->deliveries as $data) {

            $customer = Customer::where(
                'local_id',
                $data['customer_local_id']
            )->first();

            if (! $customer) {
                continue;
            }

            Delivery::updateOrCreate(
                ['invoice_no' => $data['invoice_no']],
                [
                    'sales_date' => $data['sales_date'],
                    'customer_id' => $customer->id,
                    'amount' => $data['amount'],
                    'payment_type' => $data['payment_type'],
                    'delivery_date' => $data['delivery_date'],
                    'status' => 'pending',
                    'deliveryboy_id' => $data['deliveryboy_id']
                ]
            );
        }

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Deliveries uploaded successfully'
                ]
        ]);
    }
}
