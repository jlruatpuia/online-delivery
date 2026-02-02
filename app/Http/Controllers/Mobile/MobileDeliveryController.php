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

        $deliveries = Delivery::with('customer')
            ->where('deliveryboy_id', $userId)
            ->latest()
            ->get()
            ->map(function ($delivery) {
               $mapLocation = $delivery->customer?->map_location;
                $staticMapUrl = null;
                $navigationUrl = navigationUrlFromMap($mapLocation);
                $lat = $mapLocation['lat'] ?? null;
                $lng = $mapLocation['lng'] ?? null;

                if($lat && $lng) {
                    $staticMapUrl = "https://maps.googleapis.com/maps/api/staticmap"
                        . "?center={$lat},{$lng}"
                        . "&zoom=15"
                        . "&size=400x450"
                        . "&markers=color:red|{$lat},{$lng}"
                        . "&key=" . config('services.google.maps_key');

                }
               return[
                   'id' => $delivery->id,
                   'invoice_no' => $delivery->invoice_no,
                   'amount' => $delivery->amount,
                   'status' => $delivery->status,
                   'status_color' => $delivery->status_color,
                   'payment_type' => $delivery->payment_type,
                   'delivery_datetime' => $delivery->delivery_datetime,
                    'created_at' => $delivery->created_at,
                   'customer' => [
                       'name' => $delivery->customer?->name,
                       'phone' => $delivery->customer?->phone_no,
                       'address' => $delivery->customer?->address,
                       'map_location' => [
                           'lat' => $delivery->customer?->map_location['lat'] ?? '',
                           'lng' => $delivery->customer?->map_location['lng'] ?? ''
                       ]
                   ],

                   // 👇 MAP RELATED (THIS IS KEY)
                   'has_map_location' => !empty($navigationUrl),
                   'navigation_url' => $navigationUrl,
                   'staticMapUrl' => $staticMapUrl,
               ];
            });
        return view('mobile.deliveries.index', [
            'pending' => $deliveries->where('status', 'pending'),
            'completed' => $deliveries->where('status', 'delivered'),
            'cancelled' => $deliveries->whereIn('status', ['cancelled']),
        ]);
//        return view('mobile.deliveries.index', [
//            'pending' => Delivery::where('deliveryboy_id', $userId)
//                ->whereIn('status', ['pending','assigned'])->get(),
//
//            'completed' => Delivery::where('deliveryboy_id', $userId)
//                ->where('status', 'delivered')->get(),
//
//            'cancelled' => Delivery::where('deliveryboy_id', $userId)
//                ->whereIn('status', ['cancelled','reschedule_requested'])->get(),
//        ]);
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
        $mapLocation = $delivery->customer?->map_location;
        $navigationUrl = navigationUrlFromMap($mapLocation);

        return view('mobile.deliveries.show', [
            'delivery' => [
                'id' => $delivery->id,
                'invoice_no' => $delivery->invoice_no,
                'amount' => $delivery->amount,
                'status' => $delivery->status,
                'payment_type' => $delivery->payment_type,

                'customer' => [
                    'name' => $delivery->customer?->name,
                    'phone' => $delivery->customer?->phone_no,
                    'address' => $delivery->customer?->address,
                ],

                // 👇 MAP DATA
                'has_map_location' => !empty($navigationUrl),
                'navigation_url' => $navigationUrl,
            ]
        ]);
//        $delivery->load('customer');

//        return view('mobile.deliveries.show', compact('delivery'));
    }
}
