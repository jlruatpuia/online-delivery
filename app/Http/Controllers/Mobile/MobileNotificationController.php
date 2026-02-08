<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MobileNotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->get();

        return view(
            'mobile.notifications.index',
            compact('notifications')
        );
    }

    public function markRead($id, Request $request)
    {
        $notification = $request->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllRead(Request $request)
    {
        $request->user()
            ->unreadNotifications
            ->markAsRead();

        return response()->json(['success' => true]);
    }
}
