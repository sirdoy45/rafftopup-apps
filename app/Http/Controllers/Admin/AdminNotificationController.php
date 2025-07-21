<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminNotificationController extends Controller
{
    public function check()
    {
        /** @var User $user */
        $user = auth()->user();

        $notifications = $user->unreadNotifications()->take(5)->get();
        $notifCount = $notifications->count();

        $html = view('partials.notifikasi-admin', compact('notifications'))->render();

        return response()->json([
            'count' => $notifCount,
            'html' => $html,
        ]);
    }
}
