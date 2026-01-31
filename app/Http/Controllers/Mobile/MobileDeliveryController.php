<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;

class MobileDeliveryController extends Controller
{
    private function guard(Delivery $delivery)
    {
        if ($delivery->deliveryboy_id !== auth()->id()) {
            return redirect()
                ->route('mobile.deliveries')
                ->with('error', 'This delivery is not assigned to you.');
        }
        return null;
    }
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
//        abort_if(
//            $delivery->deliveryboy_id !== auth()->id(),
//            403
//        );
        if($response = $this->guard($delivery)) {
            return $response;
        }

        $delivery->load('customer');

        return view('mobile.deliveries.show', compact('delivery'));
    }
}
