<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\User;
use App\Notifications\DeliveryRequestAcceptedNotification;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        $delivery_boys = User::where('role', 'delivery_boy')->where('is_active', true)->get();
        $deliveries = collect();

        if($request->filled('date_range')){
            $query = Delivery::with('deliveryBoy')
                ->orderByDesc('delivery_date');
            $range = $request->date_range;
            if(str_contains($range, ' to ')){
                [$from, $to] = explode(' to ', $request->date_range);
            } else {
                $from = $to = $range;
            }

            $query->whereBetween('delivery_date', [$from, $to]);

            if($request->filled('delivery_boy')){
                $query->where('deliveryboy_id', $request->delivery_boy);
            }
            if($request->filled('status')) {
                $query->where('status', $request->status);
            }
            $deliveries = $query->get();
        }

        return view('admin.deliveries.index', compact('deliveries', 'delivery_boys'));
    }

    public function show(Delivery $delivery){

        return view(
            'admin.deliveries.show',
            compact('delivery')
        );
    }

    public function approve(Delivery $delivery) {
        if ($delivery->status === 'cancel_requested') {
            $delivery->update(['status' => 'cancelled']);
        }

        if ($delivery->status === 'reschedule_requested') {
            $delivery->update([
                'status' => 'pending',
                'delivery_date' => $delivery->rescheduled_at,
                'rescheduled_at' => null,
            ]);
        }

        $delivery->deliveryBoy?->notify(
            new DeliveryRequestAcceptedNotification($delivery)
        );

        return redirect()->route('admin.deliveries.show', $delivery)
            ->with('success', 'Request approved');
    }
}
