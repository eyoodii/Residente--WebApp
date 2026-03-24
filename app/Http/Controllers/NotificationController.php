<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display all notifications
     */
    public function index()
    {
        $resident = Auth::user();
        
        $notifications = $resident->notifications()
            ->paginate(20);
        
        $unreadCount = $resident->unreadNotifications()->count();
        
        return view('citizen.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(Notification $notification)
    {
        $resident = Auth::user();
        
        // Ensure notification belongs to the authenticated resident
        if ($notification->resident_id !== $resident->id) {
            abort(403);
        }

        $notification->markAsRead();

        // Redirect to action URL if provided
        if ($notification->action_url) {
            return redirect($notification->action_url);
        }

        return back();
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $resident = Auth::user();
        
        $resident->unreadNotifications()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'All notifications marked as read!');
    }

    /**
     * Delete a notification
     */
    public function destroy(Notification $notification)
    {
        $resident = Auth::user();
        
        // Ensure notification belongs to the authenticated resident
        if ($notification->resident_id !== $resident->id) {
            abort(403);
        }

        $notification->delete();

        return back()->with('success', 'Notification deleted!');
    }

    /**
     * Get unread notification count (for AJAX requests)
     */
    public function unreadCount()
    {
        $resident = Auth::user();
        
        return response()->json([
            'count' => $resident->unreadNotifications()->count(),
        ]);
    }
}
