<?php

namespace App\Services;

use App\Models\AdminAuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuditLogger
{
    public function log(string $action, ?Model $subject = null, array $metadata = [], ?Request $request = null): void
    {
        $request = $request ?: request();
        $actor = Auth::guard('admins')->user();

        AdminAuditLog::create([
            'actor_id' => $actor ? $actor->id : null,
            'actor_guard' => 'admins',
            'action' => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->getKey() : null,
            'metadata' => $metadata,
            'ip_address' => $request ? $request->ip() : null,
            'user_agent' => $request ? (string) $request->userAgent() : null,
        ]);
    }
}
