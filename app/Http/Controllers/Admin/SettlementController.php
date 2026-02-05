<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settlement;
use App\Models\User;
use App\Notifications\SettlementStatusNotification;
use Illuminate\Http\Request;

class SettlementController extends Controller
{
    public function index(Request $request)
    {
        $query = Settlement::with('deliveryBoy')
            ->orderByDesc('settlement_date');

        if($request->filled('date_range')){
            $range = $request->date_range;
            if(str_contains($range, ' to ')){
                [$from, $to] = explode(' to ', $request->date_range);
            } else {
                $from = $to = $range;
            }

            $query->whereBetween('settlement_date', [$from, $to]);
        }
        if($request->filled('delivery_boy')){
            $query->where('deliveryboy_id', $request->delivery_boy);
        }
        if($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $settlements = $query->get();

        $delivery_boys = User::where('role', 'delivery_boy')->where('is_active', true)->get();

        return view('admin.settlements.index', compact('settlements', 'delivery_boys'));
    }

    public function show(Settlement $settlement)
    {
        return view(
            'admin.settlements.show',
            compact('settlement')
        );
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
