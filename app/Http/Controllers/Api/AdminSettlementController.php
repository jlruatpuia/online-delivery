<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettlementRejectRequest;
use App\Models\Settlement;
use App\Notifications\SettlementStatusNotification;
use Illuminate\Http\Request;

class AdminSettlementController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from_date ?? now();
        $to = $request->to_date ?? now();
        $status = $request->status ?? 'submitted';
        $settlements = Settlement::with('deliveryBoy')
            ->where('status', $status)
            ->whereBetween('settlement_date', [$from, $to])
            ->latest()
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'delivery_boy_id' => $s->deliveryboy_id,
                    'delivery_boy_name' => $s->deliveryBoy->name ?? '',
                    'settlement_date' => $s->settlement_date->toDateString(),
                    'total_amount' => $s->total_amount,
                    'total_cash' => $s->total_cash,
                    'total_upi' => $s->total_upi,
                    'status' => $s->status,
                    'submitted_at' => $s->created_at->toDateTimeString(),
                ];
            });
        return response()->json([
            'success' => true,
            'data' => $settlements
        ]);
    }

    public function get($id){
        $settlement = Settlement::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $settlement
        ]);
    }

    public function approve(Settlement $settlement)
    {
        $settlement->update(['status' => 'approved']);

        $settlement->deliveryBoy->notify(
            new SettlementStatusNotification($settlement, 'approved')
        );

        return response()->json([
            'success' => true,
            'message' => 'Settlement approved'
        ]);
    }

    public function reject(
        SettlementRejectRequest $request,
        Settlement $settlement
    ) {
        $settlement->update([
            'status' => 'rejected',
            'reject_reason' => $request->reason,
        ]);

        $settlement->deliveryBoy->notify(
            new SettlementStatusNotification($settlement, 'rejected')
        );

        return response()->json([
            'success' => true,
            'message' => 'Settlement rejected'
        ]);
    }
}
