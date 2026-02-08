<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class DeliveryBoyPerformanceController extends Controller
{
    public function index(Request $request)
    {
        // 📆 Handle date range
        if ($request->filled('date_range')) {
            if (str_contains($request->date_range, ' to ')) {
                [$from, $to] = explode(' to ', $request->date_range);
            } else {
                $from = $to = $request->date_range;
            }
        } else {
            $from = now()->startOfMonth()->toDateString();
            $to   = now()->toDateString();
        }

        $performance = User::where('role', 'delivery_boy')
            ->where('is_active', true)
            ->withCount([
                'deliveries as total_deliveries' => fn ($q) =>
                $q->whereBetween('delivery_date', [$from, $to]),

                'deliveries as delivered' => fn ($q) =>
                $q->whereBetween('delivery_date', [$from, $to])
                    ->where('status', 'delivered'),

                'deliveries as cancelled' => fn ($q) =>
                $q->whereBetween('delivery_date', [$from, $to])
                    ->where('status', 'cancelled'),

                'deliveries as rescheduled' => fn ($q) =>
                $q->whereBetween('delivery_date', [$from, $to])
                    ->where('status', 'rescheduled'),
            ])
            ->get()
            ->map(function ($boy) use ($from, $to) {

                $cash = Payment::where('deliveryboy_id', $boy->id)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('payment_type', 'cash')
                    ->sum('amount');

                $upi = Payment::where('deliveryboy_id', $boy->id)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('payment_type', 'upi')
                    ->sum('amount');

                $success = $boy->total_deliveries > 0
                    ? round(($boy->delivered / $boy->total_deliveries) * 100, 2)
                    : 0;

                return [
                    'id' => $boy->id,
                    'name' => $boy->name,
                    'total' => $boy->total_deliveries,
                    'delivered' => $boy->delivered,
                    'cancelled' => $boy->cancelled,
                    'rescheduled' => $boy->rescheduled,
                    'success_rate' => $success,
                    'cash' => $cash,
                    'upi' => $upi,
                    'total_amount' => $cash + $upi,
                ];
            });

        return view(
            'admin.delivery_boys.performance',
            compact('performance', 'from', 'to')
        );
    }

    public function show(User $user, Request $request)
    {
        // 📆 Date range handling (already correct)
        if ($request->filled('date_range')) {
            if (str_contains($request->date_range, ' to ')) {
                [$from, $to] = explode(' to ', $request->date_range);
            } else {
                $from = $to = $request->date_range;
            }
        } else {
            $from = now()->startOfMonth()->toDateString();
            $to   = now()->toDateString();
        }

        $deliveriesQuery = Delivery::where('deliveryboy_id', $user->id)
            ->whereBetween('delivery_date', [$from, $to]);

        // 📦 Status counts
        $statusCounts = [
            'pending'     => (clone $deliveriesQuery)->where('status', 'pending')->count(),
            'delivered'   => (clone $deliveriesQuery)->where('status', 'delivered')->count(),
            'rescheduled' => (clone $deliveriesQuery)->where('status', 'rescheduled')->count(),
            'cancelled'   => (clone $deliveriesQuery)->where('status', 'cancelled')->count(),
        ];

        // 💰 Payment summary
        $codCount = (clone $deliveriesQuery)
            ->where('payment_type', 'cod')
            ->count();

        $codAmount = (clone $deliveriesQuery)
            ->where('payment_type', 'cod')
            ->sum('amount');

        $prepaidCount = (clone $deliveriesQuery)
            ->where('payment_type', 'prepaid')
            ->count();

        $prepaidAmount = (clone $deliveriesQuery)
            ->where('payment_type', 'prepaid')
            ->sum('amount');

        $totalCount = (clone  $deliveriesQuery)
            ->whereIn('payment_type', ['cod', 'prepaid'])
            ->count();

        $totalAmount = $codAmount + $prepaidAmount;

        // 📄 Delivery list (for table)
        $deliveries = $deliveriesQuery
            ->latest()
            ->get();

        return view(
            'admin.delivery_boys.show',
            compact(
                'user',
                'deliveries',
                'from',
                'to',
                'statusCounts',
                'codCount',
                'codAmount',
                'prepaidCount',
                'prepaidAmount',
                'totalAmount',
                'totalCount'
            )
        );
    }

}
