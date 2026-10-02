<?php

namespace App\Http\Middleware;

use App\Models\Agency;
use Closure;

class AgencyAccess
{
    public function handle($request, Closure $next)
    {
        $user = auth('admins')->user();
        if (! $user) {
            return redirect()->route('admin.login');
        }
        if ((int) $user->status !== 1 || ! $user->can('quan-ly-dai-ly')) {
            return response()->view('errors.403', [], 403);
        }
        $agency = Agency::where('user_id', $user->id)->where('active', true)->first();
        if (! $agency) {
            return response()->view('errors.403', [], 403);
        }
        $request->attributes->set('agency', $agency);

        return $next($request);
    }
}
