<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\Settlement;
use App\Models\User;
use Illuminate\Http\Request;

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
            'kpi' => $this->kpi($from, $to),
            'daily_chart' => $this->dailyChart($from, $to),
            'monthly_chart' => $this->monthlyChart(),
            'delivery_status_chart' => $this->deliveryStatusChart($from, $to),
            'payment_method_chart' => $this->paymentMethodChart($from, $to),
        ]);
    }

    /**
     * TOP KPI CARDS
     */
    private function kpi($from, $to)
    {
        return [
            'total_deliveries' => Delivery::count(),

            'pending_deliveries' => Delivery::whereIn(
                'status', ['pending','assigned']
            )->count(),

            'delivered_deliveries' => Delivery::where(
                'status','delivered'
            )->count(),

            'total_collection' => Payment::sum('amount'),

            'cash_collection' => Payment::where(
                'payment_method','cash'
            )->sum('amount'),

            'upi_collection' => Payment::where(
                'payment_method','upi'
            )->sum('amount'),

            'pending_settlements' => Settlement::where(
                'status','submitted'
            )->count(),

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
                DATE(created_at) as date,
                COUNT(*) as deliveries,
                SUM(amount) as total_amount
            ")
            ->whereBetween('created_at', [$from, $to])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
    }

    /**
     * 📅 MONTHLY CHART (Last 12 Months)
     */
    private function monthlyChart()
    {
        return Delivery::selectRaw("
                DATE_FORMAT(created_at,'%Y-%m') as month,
                COUNT(*) as deliveries,
                SUM(amount) as total_amount
            ")
            ->where('created_at', '>=', now()->subMonths(11))
            ->groupBy(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"))
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
            ->whereBetween('created_at', [$from, $to])
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
