<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->unreadNotifications()
            ->latest()
            ->take(50)
            ->get()
            ->map(function ($n) {
                $data = $n->data ?? [];
                return [
                    'id' => $n->id,
                    'type' => class_basename($n->type),

                    //common fields
                    'title' => $n->data['title'] ?? 'Notification',
                    'message' => $n->data['message'] ?? '',
                    'is_read' => $n->read_at !== null,
                    'created_at' => $n->created_at->toDateTimeString(),
                    'reason' => $n->data['reason'] ?? '',

                    // 👇 Deep-link targets (optional)
                    'settlement_id' => $data['settlement_id'] ?? null,
                    'delivery_id' => $data['delivery_id'] ?? null,
                    'request_type' => $data['request_type'] ?? null
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    public function markAsRead($id, Request $request)
    {
        $notification = $request->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json([
            'success' => true
        ]);
    }

    public function readAll(Request $request) {
        $request->user()
            ->unreadNotifications
            ->markAsRead();

        return response()->json(['success' => true]);
    }
}
