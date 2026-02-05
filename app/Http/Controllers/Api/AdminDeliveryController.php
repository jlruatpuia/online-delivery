<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Notifications\DeliveryRequestAcceptedNotification;
use App\Notifications\DeliveryRequestRejectedNotification;
use Illuminate\Http\Request;

class AdminDeliveryController extends Controller
{
    public function index(Request $request)
    {
        $query = Delivery::with(['deliveryBoy','customer']);

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('delivery_date', [
                $request->from_date,
                $request->to_date
            ]);
        }

        if ($request->deliveryboy_id) {
            $query->where('deliveryboy_id', $request->deliveryboy_id);
        }

        return response()->json(
            $query->latest()->get()
        );
    }

    public function get($id) {
        $delivery = Delivery::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $delivery
        ]);
    }

    public function approveRequest(Delivery $delivery)
    {
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

        return response()->json([
            'success' => true,
            'message' => 'Request approved'
        ]);
    }

    public function rejectRequest(Request $request, Delivery $delivery)
    {
        $request->validate([
            'reason' => 'required|string'
        ]);

        $delivery->update(['status' => 'pending']);

        $delivery->deliveryBoy?->notify(
            new DeliveryRequestRejectedNotification(
                $delivery,
                $request->reason
            )
        );

        return response()->json([
            'success' => true,
            'message' => 'Request rejected'
        ]);
    }
}
