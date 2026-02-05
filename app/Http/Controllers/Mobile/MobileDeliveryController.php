<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Upi;
use Carbon\Carbon;
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
            ->whereDate('delivery_date', Carbon::today())
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
                    'delivered_at' => $delivery->delivered_at,
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
            'cancelled' => $deliveries->whereIn('status', ['reschedule_requested', 'cencel_requested','cancelled']),
        ]);
    }

    public function show(Delivery $delivery)
    {
        if($response = $this->guard($delivery)) {
            return $response;
        }
        $mapLocation = $delivery->customer?->map_location;
        $navigationUrl = navigationUrlFromMap($mapLocation);
        $upi = Upi::first();

        return view('mobile.deliveries.show', [
            'delivery' => [
                'id' => $delivery->id,
                'invoice_no' => $delivery->invoice_no,
                'amount' => $delivery->amount,
                'status' => $delivery->status,
                'payment_type' => $delivery->payment_type,
                'delivered_at' => $delivery->delivered_at,

                'customer' => [
                    'name' => $delivery->customer?->name,
                    'phone' => $delivery->customer?->phone_no,
                    'address' => $delivery->customer?->address,
                ],

                // 👇 MAP DATA
                'has_map_location' => !empty($navigationUrl),
                'navigation_url' => $navigationUrl,

                'upi' => "upi://pay?pa=".$upi->upi_id."&pn=".$upi->payee_name."&am=".$delivery->amount."&tn=".$delivery->invoice_no."&cu=INR"
            ]
        ]);
    }
}
