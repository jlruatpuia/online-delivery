<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MobileDashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        /* Delivery counts */
        $pendingCount = Delivery::where('deliveryboy_id', $userId)
            ->whereIn('status', ['pending', 'assigned'])
            ->count();

        $completedCount = Delivery::where('deliveryboy_id', $userId)
            ->where('status', 'delivered')
            ->count();

        /* Payment totals */
        $totalPrepaid = Payment::where('deliveryboy_id', $userId)
            ->where('payment_type', 'prepaid')
            ->where('status', 'verified')
            ->sum('amount');

        $totalCash = Payment::where('deliveryboy_id', $userId)
            ->where('payment_method', 'cash')
            ->where('status', 'verified')
            ->sum('amount');

        $totalUpi = Payment::where('deliveryboy_id', $userId)
            ->where('payment_method', 'upi')
            ->where('status', 'verified')
            ->sum('amount');

        // ✅ NET TOTAL = CASH + UPI
        $netTotal = $totalCash + $totalUpi;

        /* Recent activities (deliveries + payments) */
        $recentDeliveries = Delivery::where('deliveryboy_id', $userId)
            ->latest()
            ->limit(5)
            ->get();

        return view('mobile.dashboard', compact(
            'pendingCount',
            'completedCount',
            'totalPrepaid',
            'totalCash',
            'totalUpi',
            'recentDeliveries',
            'netTotal'
        ));
    }
}
