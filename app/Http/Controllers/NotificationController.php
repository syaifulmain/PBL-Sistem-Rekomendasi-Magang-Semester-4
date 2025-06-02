<?php

namespace App\Http\Controllers;

use App\Models\NotificationModel;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public static function createNotification($userId, $message, $title, $url)
    {
        $notification = new NotificationModel();
        $notification->type = 'App\\Notifications\\PengajuanMagangNotification';
        $notification->notifiable_type = 'App\\Models\\UserModel';
        $notification->notifiable_id = $userId;
        $notification->data = [
            'title' => $title,
            'message' => $message,
            'url' => $url
        ];
        $notification->save();

        return $notification;
    }

    public function readAll()
    {
        auth()->user()->notifications()->update(['read_at' => now()]);
        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi telah ditandai sebagai terbaca.'
        ]);
    }

    public function markRead($notification)
    {
        $notification = auth()->user()->notifications()->findOrFail($notification);
        $notification->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }
}