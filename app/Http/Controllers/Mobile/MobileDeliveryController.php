<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;

class MobileDeliveryController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        return view('mobile.deliveries.index', [
            'pending' => Delivery::where('deliveryboy_id', $userId)
                ->whereIn('status', ['pending','assigned'])->get(),

            'completed' => Delivery::where('deliveryboy_id', $userId)
                ->where('status', 'delivered')->get(),

            'cancelled' => Delivery::where('deliveryboy_id', $userId)
                ->whereIn('status', ['cancelled','reschedule_requested'])->get(),
        ]);
    }

    public function show(Delivery $delivery)
    {
        // Security: only assigned delivery boy can view
        abort_if(
            $delivery->deliveryboy_id !== auth()->id(),
            403
        );

        $delivery->load('customer');

        return view('mobile.deliveries.show', compact('delivery'));
    }
}
