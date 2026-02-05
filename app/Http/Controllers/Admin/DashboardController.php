<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /* Overall statistics */
        $stats = [
            'total' => Delivery::count(),
            'delivered' => Delivery::where('status', 'delivered')->count(),
            'pending' => Delivery::whereIn('status', ['pending', 'assigned'])->count(),
            'cancelled' => Delivery::where('status', 'cancelled')->count(),
            'rescheduled' => Delivery::where('status', 'reschedule_requested')->count(),
            'total_amount' => Delivery::where('status', 'delivered')->sum('amount'),
        ];

        /* Per delivery boy statistics */
        $deliveryBoys = User::where('role', 'delivery_boy')
            ->withCount([
                'deliveries as total_deliveries',
                'deliveries as delivered_count' => fn ($q) =>
                $q->where('status', 'delivered'),

                'deliveries as pending_count' => fn ($q) =>
                $q->whereIn('status', ['pending', 'assigned']),

                'deliveries as cancelled_count' => fn ($q) =>
                $q->where('status', 'cancelled'),

                'deliveries as rescheduled_count' => fn ($q) =>
                $q->where('status', 'reschedule_requested'),
            ])
            ->withSum([
                'deliveries as total_amount' => fn ($q) =>
                $q->where('status', 'delivered')
            ], 'amount')
            ->get();
        /* Delivery Status Summary */
        $deliveryStatus = Delivery::select(
            'status',
            DB::raw('count(*) as total')
        )
            ->groupBy('status')
            ->pluck('total', 'status');

        // 🔹 Delivery boy performance
        $deliveryBoyPerformance = Delivery::select(
            'deliveryboy_id',
            DB::raw('count(*) as total'),
            DB::raw("sum(case when status='delivered' then 1 else 0 end) as delivered")
        )
            ->with('deliveryBoy:id,name')
            ->groupBy('deliveryboy_id')
            ->get()
            ->map(function ($row) {
                $successRate = $row->total > 0
                    ? round(($row->delivered / $row->total) * 100, 2)
                    : 0;

                return [
                    'name' => $row->deliveryBoy->name ?? 'N/A',
                    'total' => $row->total,
                    'delivered' => $row->delivered,
                    'success_rate' => $successRate,
                ];
            });
        /* Latest deliveries (read-only list) */
        // 🔹 Payment type summary (Cash vs UPI)
        $paymentSummary = Payment::select(
            'payment_method',
            DB::raw('sum(amount) as total')
        )
            ->whereNotNull('payment_method')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        return view('admin.dashboard', compact(
            'stats',
            'deliveryBoys',
            'deliveryStatus',
            'deliveryBoyPerformance',
            'paymentSummary'
        ));
    }
}
