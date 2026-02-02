<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->take(50)
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'type' => class_basename($n->type),
                    'title' => $n->data['title'] ?? 'Notification',
                    'message' => $n->data['message'] ?? '',
                    'is_read' => $n->read_at !== null,
                    'created_at' => $n->created_at->toDateTimeString(),
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
}
