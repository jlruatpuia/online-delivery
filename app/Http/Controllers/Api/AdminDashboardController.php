<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\Settlement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from_date
            ? now()->parse($request->from_date)->startOfDay()
            : now()->startOfMonth();

        $to = $request->to_date
            ? now()->parse($request->to_date)->endOfDay()
            : now();

        return response()->json([
            'success' => true,
            'data' => [
                'kpi' => $this->kpi($from, $to),
                'daily_chart' => $this->dailyChart($from, $to),
                'monthly_chart' => $this->monthlyChart(),
                'delivery_status_chart' => $this->deliveryStatusChart($from, $to),
                'payment_method_chart' => $this->paymentMethodChart($from, $to),
            ]
        ]);
    }

    /**
     * TOP KPI CARDS
     */
    private function kpi($from, $to)
    {
        return [
            'total_deliveries' => Delivery::whereBetween('delivery_date', [$from, $to])
                ->count(),

            'pending_deliveries' => Delivery::whereIn(
                'status', ['pending','assigned']
            )
                ->whereBetween('delivery_date', [$from, $to])
                ->count(),

            'delivered_deliveries' => Delivery::where(
                'status','delivered'
            )
                ->whereBetween('delivery_date', [$from, $to])
                ->count(),

            'total_collection' => Payment::whereBetween('created_at', [$from, $to])
                ->sum('amount'),

            'cash_collection' => Payment::where(
                'payment_method','cash'
            )
                ->whereBetween('created_at', [$from, $to])
                ->sum('amount'),

            'upi_collection' => Payment::where(
                'payment_method','upi'
            )
                ->whereBetween('created_at', [$from, $to])
                ->sum('amount'),

            'pending_settlements' => Settlement::where(
                'status','submitted'
            )->whereBetween('settlement_date', [$from, $to])
                ->count(),

            'active_delivery_boys' => User::where(
                'role','delivery_boy'
            )->where('is_active', true)->count(),
        ];
    }

    /**
     * 📆 DAILY CHART (Deliveries + Amount)
     */
    private function dailyChart($from, $to)
    {
        return Delivery::selectRaw("
                DATE(delivery_date) as date,
                COUNT(*) as deliveries,
                SUM(amount) as total_amount
            ")
            ->whereBetween('delivery_date', [$from, $to])
            ->groupBy(DB::raw('DATE(delivery_date)'))
            ->orderBy('date')
            ->get();
    }

    /**
     * 📅 MONTHLY CHART (Last 12 Months)
     */
    private function monthlyChart()
    {
        return Delivery::selectRaw("
                DATE_FORMAT(delivery_date,'%Y-%m') as month,
                COUNT(*) as deliveries,
                SUM(amount) as total_amount
            ")
            ->where('delivery_date', '>=', now()->subMonths(11))
            ->groupBy(DB::raw("DATE_FORMAT(delivery_date,'%Y-%m')"))
            ->orderBy('month')
            ->get();
    }

    /**
     * 📦 DELIVERY STATUS PIE CHART
     */
    private function deliveryStatusChart($from, $to)
    {
        return Delivery::selectRaw("
                status,
                COUNT(*) as total
            ")
            ->whereBetween('delivery_date', [$from, $to])
            ->groupBy('status')
            ->get();
    }

    /**
     * 💳 PAYMENT METHOD PIE CHART
     */
    private function paymentMethodChart($from, $to)
    {
        return Payment::selectRaw("
                payment_method,
                SUM(amount) as total_amount
            ")
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('payment_method')
            ->get();
    }
}
