<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettlementRejectRequest;
use App\Models\Settlement;
use App\Notifications\SettlementStatusNotification;
use Illuminate\Http\Request;

class AdminSettlementController extends Controller
{
    public function index()
    {
        return response()->json(
            Settlement::with('deliveryBoy')->latest()->get()
        );
    }

    public function approve(Settlement $settlement)
    {
        $settlement->update(['status' => 'approved']);

        $settlement->deliveryBoy->notify(
            new SettlementStatusNotification($settlement, 'approved')
        );

        return response()->json(['message' => 'Settlement approved']);
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

        return response()->json(['message' => 'Settlement rejected']);
    }
}
