<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Notifications\SettlementStatusNotification;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * List all payments (admin view)
     */
    public function index(Request $request)
    {
        $payments = Payment::with([
            'delivery',
            'deliveryBoy'
        ])
            ->latest()
            ->get();

        $summary = [
            'total' => $payments->count(),
            'pending' => $payments->where('status', 'pending')->count(),
            'verified' => $payments->where('status', 'verified')->count(),
            'rejected' => $payments->where('status', 'rejected')->count(),
            'total_amount' => $payments
                ->where('status', 'verified')
                ->sum('amount'),
        ];

        return view('admin.payments.index', compact(
            'payments',
            'summary'
        ));
    }

    /**
     * Verify a payment
     */
    public function verify(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'This payment is already processed.');
        }

        $payment->update([
            'status' => 'verified',
        ]);

        // Notify delivery boy
        $payment->deliveryBoy->notify(
            new SettlementStatusNotification($payment, 'verified')
        );

        return back()->with('success', 'Payment verified successfully.');
    }

    /**
     * Reject a payment
     */
    public function reject(Request $request, Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'This payment is already processed.');
        }

        $payment->update([
            'status' => 'rejected',
        ]);

        // Notify delivery boy
        $payment->deliveryBoy->notify(
            new SettlementStatusNotification($payment, 'rejected')
        );

        return back()->with('success', 'Payment rejected.');
    }
}
