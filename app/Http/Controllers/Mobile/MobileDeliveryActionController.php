<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\DeliveryRequestNotification;
use Illuminate\Http\Request;

class MobileDeliveryActionController extends Controller
{
    private function guard(Delivery $delivery)
    {
        if ($delivery->deliveryboy_id !== auth()->id()) {
            return redirect()
                ->route('mobile.deliveries')
                ->with('error', 'This delivery is not assigned to you.');
        }

        if (! in_array($delivery->status, ['pending', 'assigned'])) {
            return redirect()
                ->route('mobile.deliveries')
                ->with('error', 'This delivery is already completed or closed.');
        }

        return null;
    }

    /* 1️⃣ PREPAID – CONFIRM DELIVERY */
    public function confirmPrepaid(Delivery $delivery)
    {
        if($response = $this->guard($delivery)) {
            return $response;
        }

        Payment::create([
            'delivery_id' => $delivery->id,
            'deliveryboy_id' => auth()->id(),
            'amount' => $delivery->amount,
            'payment_type' => 'prepaid' // prepaid auto-verified
        ]);

        $delivery->update(['status' => 'delivered']);

        return back()->with('success', 'Delivery completed');
    }

    /* 2️⃣ COD – CASH / UPI */
    public function collectCod(Request $request, Delivery $delivery)
    {
        if($response = $this->guard($delivery)) {
            return $response;
        }

        $request->validate([
            'payment_method' => 'required|in:cash,upi',

        ]);

        Payment::create([
            'delivery_id' => $delivery->id,
            'deliveryboy_id' => auth()->id(),
            'amount' => $delivery->amount,
            'payment_type' => 'cod',
            'payment_method' => $request->payment_method,
            'upi_ref_no' => $request->upi_ref_no
        ]);

        $delivery->update(['status' => 'delivered']);

        return back()->with('success', 'Payment submitted');
    }

    /* 4️⃣ RESCHEDULE / CANCEL REQUEST */
    public function requestChange(Request $request, Delivery $delivery)
    {
        if($response = $this->guard($delivery)) {
            return $response;
        }

        $request->validate([
            'type' => 'required|in:reschedule,cancel',
            'reason' => 'required|string|max:255',
        ]);

        $delivery->update([
            'status' => $request->type === 'cancel'
                ? 'cancel_requested'
                : 'reschedule_requested',
        ]);

        // notify admin here (optional)
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(
                new DeliveryRequestNotification(
                    $delivery,
                    $request->type,
                    $request->reason
                )
            );
        }

        return back()->with('success', 'Request sent to admin');
    }
}
