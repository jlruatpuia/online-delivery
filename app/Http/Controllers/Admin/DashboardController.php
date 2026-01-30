<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\User;
use Illuminate\Http\Request;

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

        /* Latest deliveries (read-only list) */
        $deliveries = Delivery::with('deliveryBoy')
            ->latest()
            ->limit(50)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'deliveryBoys',
            'deliveries'
        ));
    }
}
