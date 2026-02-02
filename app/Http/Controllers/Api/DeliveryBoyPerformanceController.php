<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Payment;
use Illuminate\Http\Request;

class DeliveryBoyPerformanceController extends Controller
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
                'deliveries_chart' => $this->deliveriesChart($from, $to),
                'collection_chart' => $this->collectionChart($from, $to),
                'success_rate_chart' => $this->successRateChart($from, $to),
                ]
        ]);
    }

    /**
     * 📦 Deliveries count per delivery boy
     */
    private function deliveriesChart($from, $to)
    {
        return Delivery::selectRaw("
                deliveryboy_id,
                COUNT(*) as total_deliveries,
                SUM(status = 'delivered') as delivered
            ")
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('deliveryboy_id')
            ->with('deliveryBoy:id,name')
            ->get();
    }

    /**
     * 💰 Collection per delivery boy
     */
    private function collectionChart($from, $to)
    {
        return Payment::selectRaw("
                deliveryboy_id,
                SUM(amount) as total_amount,
                SUM(payment_method = 'cash') as cash_count,
                SUM(payment_method = 'upi') as upi_count
            ")
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('deliveryboy_id')
            ->with('deliveryBoy:id,name')
            ->get();
    }

    /**
     * ✅ Success rate (% delivered)
     */
    private function successRateChart($from, $to)
    {
        return Delivery::selectRaw("
                deliveryboy_id,
                COUNT(*) as total,
                SUM(status = 'delivered') as delivered,
                ROUND(
                    (SUM(status = 'delivered') / COUNT(*)) * 100,
                    2
                ) as success_rate
            ")
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('deliveryboy_id')
            ->with('deliveryBoy:id,name')
            ->get();
    }
}
