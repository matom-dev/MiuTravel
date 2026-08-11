<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead(Request $request, $id = null)
    {
        $notifications = AppNotification::forAdmin()->unread();

        if ($id) {
            $notifications->where('id', $id);
        }

        $notifications->update(['read_at' => now()]);

        return redirect()->to($request->input('redirect_to', url()->previous()));
    }
}
