<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Payment;
use Illuminate\Http\Request;

class MobilePaymentController extends Controller
{
    /**
     * List delivery boy payments
     */
    public function index()
    {
        $userId = auth()->id();

        $payments = Payment::with('delivery')
            ->where('deliveryboy_id', $userId)
            ->latest()
            ->get();

        $summary = [
            'prepaid' => $payments
                ->where('payment_type', 'prepaid')
                ->where('status', 'verified')
                ->sum('amount'),

            'cash' => $payments
                ->where('payment_method', 'cash')
                ->where('status', 'verified')
                ->sum('amount'),

            'upi' => $payments
                ->where('payment_method', 'upi')
                ->where('status', 'verified')
                ->sum('amount'),

            'pending' => $payments
                ->where('status', 'pending')
                ->sum('amount'),
        ];

        return view('mobile.payments.index', compact(
            'payments',
            'summary'
        ));
    }

    /**
     * Submit COD payment (cash / upi)
     * Called when delivery is completed
     */
    public function store(Request $request, Delivery $delivery)
    {
        // Security: only assigned delivery boy
        if ($delivery->deliveryboy_id !== auth()->id()) {
            return redirect()
                ->route('mobile.deliveries')
                ->with('error', 'This delivery is not assigned to you.');
        }

        // Prevent double payment
        if ($delivery->payment) {
            return redirect()
                ->route('mobile.delivery.show', $delivery)
                ->with('error', 'Payment already submitted.');
        }

        $request->validate([
            'payment_method' => 'required|in:cash,upi',
            'upi_ref_no' => 'required_if:payment_method,upi|max:100',
        ]);

        Payment::create([
            'delivery_id' => $delivery->id,
            'deliveryboy_id' => auth()->id(),
            'amount' => $delivery->amount,
            'payment_type' => 'cod',
            'payment_method' => $request->payment_method,
            'upi_ref_no' => $request->upi_ref_no,
            'status' => 'pending',
        ]);

        $delivery->update([
            'status' => 'delivered',
        ]);

        return redirect()
            ->route('mobile.dashboard')
            ->with('payment_success', true)
            ->with('success', 'Payment submitted successfully.');
    }

    /**
     * Show single payment details
     */
    public function show(Payment $payment)
    {
        if ($payment->deliveryboy_id !== auth()->id()) {
            return redirect()
                ->route('mobile.payments.index')
                ->with('error', 'You cannot view this payment.');
        }

        return view('mobile.payments.show', compact('payment'));
    }
}
