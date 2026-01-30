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

        $from = $request->input('from_date');
        $to = $request->input('to_date');

        $totalAmount = 0;
        $alreadySubmitted = false;

        if ($from && $to) {
            $totalAmount = Payment::where('deliveryboy_id', $userId)
                ->where('status', 'verified')
                ->whereBetween('created_at', [
                    $from . ' 00:00:00',
                    $to . ' 23:59:59'
                ])
                ->sum('amount');

            $alreadySubmitted = Settlement::where('deliveryboy_id', $userId)
                ->where('from_date', $from)
                ->where('to_date', $to)
                ->exists();
        }

        $previousSettlements = Settlement::where('deliveryboy_id', $userId)
            ->latest()
            ->limit(5)
            ->get();

        return view('mobile.settlement.index', compact(
            'from',
            'to',
            'totalAmount',
            'alreadySubmitted',
            'previousSettlements'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $userId = auth()->id();

        if (Settlement::where('deliveryboy_id', $userId)
            ->where('from_date', $request->from_date)
            ->where('to_date', $request->to_date)
            ->exists()) {

            return back()->with('error',
                'Settlement already submitted for this date range.');
        }

        $amount = Payment::where('deliveryboy_id', $userId)
            ->where('status', 'verified')
            ->whereBetween('created_at', [
                $request->from_date.' 00:00:00',
                $request->to_date.' 23:59:59'
            ])
            ->sum('amount');

        if ($amount <= 0) {
            return back()->with('error',
                'No verified payments for this date range.');
        }

        $settlement = Settlement::create([
            'deliveryboy_id' => $userId,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'total_amount' => $amount,
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
