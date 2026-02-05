<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Settlement;
use App\Models\User;
use App\Notifications\SettlementSubmittedNotification;
use Illuminate\Http\Request;

class MobileSettlementController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $settlement_date = $request->input('settlement_date') ?? now()->toDateString();

        $totalPrepaid = 0;
        $totalUpi = 0;
        $totalCash = 0;
        $totalAmount = 0;
        $netPayable = 0;
        $alreadySubmitted = false;

        if ($settlement_date) {

            $payments = Payment::where('deliveryboy_id', $userId)
                ->whereDate('created_at', $settlement_date)
                ->get();

            $totalCash = $payments->where('payment_method', 'cash')->sum('amount');
            $totalUpi = $payments->where('payment_method', 'upi')->sum('amount');
            $totalPrepaid = $payments->where('payment_method', null)->sum('amount');

            $netPayable = $totalCash + $totalUpi;

            $alreadySubmitted = Settlement::where('deliveryboy_id', $userId)
                ->where('settlement_date', $settlement_date)
                ->exists();
        }

        $previousSettlements = Settlement::where('deliveryboy_id', $userId)
            ->latest()
            ->limit(5)
            ->get();

        return view('mobile.settlement.index', compact(
            'settlement_date',
            'totalPrepaid',
            'totalCash',
            'totalUpi',
            'totalAmount',
            'netPayable',
            'alreadySubmitted',
            'previousSettlements'
        ));
    }

    public function store(Request $request)
    {

        $request->validate([
            'settlement_date' => 'required|date',
        ]);

        $date = $request->settlement_date;

        $userId = auth()->id();

        if (Settlement::where('deliveryboy_id', $userId)
            ->where('settlement_date', $request->settlement_date)
            ->where('status', 'submitted')
            ->exists()) {

            return back()->with('error',
                'Settlement already submitted for this date range.');
        }

        $payments = Payment::where('deliveryboy_id', $userId)
            ->whereDate('created_at', $date)
            ->get();
        $totalCash = $payments->where('payment_method', 'cash')->sum('amount');
        $totalUpi = $payments->where('payment_method', 'upi')->sum('amount');

        $settlement = Settlement::create([
            'deliveryboy_id' => $userId,
            'settlement_date' => $request->settlement_date,
            'total_cash' => $totalCash,
            'total_upi' => $totalUpi,
            'total_amount' => $totalCash + $totalUpi,
            'status' => 'submitted'
        ]);

        // Notify admin
        User::where('role', 'admin')->get()
            ->each(fn ($admin) =>
            $admin->notify(
                new SettlementSubmittedNotification($settlement)
            )
            );

        return redirect()
            ->route('mobile.settlement')
            ->with('success', 'Settlement submitted successfully.');
    }
}
