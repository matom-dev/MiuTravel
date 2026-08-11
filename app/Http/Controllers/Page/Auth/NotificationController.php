<?php

namespace App\Http\Controllers\Page\Auth;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead(Request $request, $id = null)
    {
        if (!Auth::guard('users')->check()) {
            return redirect()->route('page.user.account');
        }

        $notifications = AppNotification::forUser(Auth::guard('users')->id())->unread();

        if ($id) {
            $notifications->where('id', $id);
        }

        $notifications->update(['read_at' => now()]);

        return redirect()->to($request->input('redirect_to', url()->previous()));
    }
}
