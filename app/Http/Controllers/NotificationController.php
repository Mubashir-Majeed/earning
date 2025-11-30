<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = $user->notifications()->latest();
        
        if ($request->has('unread_only')) {
            $query->where('is_read', false);
        }
        
        $notifications = $query->paginate(20);
        
        if ($request->wantsJson()) {
            return response()->json([
                'notifications' => $notifications,
                'unread_count' => $user->notifications()->where('is_read', false)->count(),
            ]);
        }
        
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications count (for AJAX).
     */
    public function unreadCount()
    {
        $user = Auth::user();
        $count = $user->notifications()->where('is_read', false)->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications for dropdown.
     */
    public function recent()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'notifications' => [],
                    'unread_count' => 0,
                    'error' => 'User not authenticated'
                ], 401);
            }
            
            $notifications = $user->notifications()
                ->latest()
                ->limit(10)
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'type' => $notification->type,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'link' => $notification->link,
                        'is_read' => (bool) $notification->is_read,
                        'created_at' => $notification->created_at ? $notification->created_at->toISOString() : null,
                    ];
                });
            
            $unreadCount = $user->notifications()->where('is_read', false)->count();
            
            return response()->json([
                'notifications' => $notifications->values()->all(),
                'unread_count' => $unreadCount,
                'success' => true
            ]);
        } catch (\Exception $e) {
            \Log::error('Notification recent error: ' . $e->getMessage());
            return response()->json([
                'notifications' => [],
                'unread_count' => 0,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->notifications()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Delete a notification.
     */
    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $notification->delete();
        
        return response()->json(['success' => true]);
    }
}
