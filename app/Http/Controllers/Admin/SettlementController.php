<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settlement;
use App\Notifications\SettlementStatusNotification;
use Illuminate\Http\Request;

class SettlementController extends Controller
{
    public function index()
    {
        $settlements = Settlement::with('deliveryBoy')
            ->latest()
            ->get();

        return view('admin.settlements.index', compact('settlements'));
    }

    public function approve(Settlement $settlement)
    {
        if ($settlement->status !== 'submitted') {
            return back()->with('error', 'Already processed.');
        }

        $settlement->update(['status' => 'approved']);

        $settlement->deliveryBoy->notify(
            new SettlementStatusNotification($settlement, 'approved')
        );

        return back()->with('success', 'Settlement approved.');
    }

    public function reject(Request $request, Settlement $settlement)
    {
        if ($settlement->status !== 'submitted') {
            return back()->with('error', 'Already processed.');
        }

        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $settlement->update([
            'status' => 'rejected',
            'reject_reason' => $request->reason,
        ]);

        $settlement->deliveryBoy->notify(
            new SettlementStatusNotification($settlement, 'rejected')
        );

        return back()->with('success', 'Settlement rejected.');
    }
}
