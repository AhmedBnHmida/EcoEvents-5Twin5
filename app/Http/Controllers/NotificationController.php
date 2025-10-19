<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mark all notifications as read for the authenticated user
     *
     * @return \Illuminate\Http\Response
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        
        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
    
    /**
     * Mark a specific notification as read
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return redirect()->back()->with('success', 'Notification marquée comme lue.');
    }
    
    /**
     * Get all notifications for the authenticated user (for AJAX)
     *
     * @return \Illuminate\Http\Response
     */
    public function getNotifications()
    {
        $notifications = Auth::user()->notifications()->latest()->limit(10)->get();
        $unreadCount = Auth::user()->unreadNotifications->count();
        
        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }
}
