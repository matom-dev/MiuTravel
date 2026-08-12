<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle($request, Closure $next, ...$permissions)
    {
        $user = Auth::guard('admins')->user() ?: Auth::user();
        $permissions = $this->normalizePermissions($permissions);

        if ($user && $user->can($permissions)) {
            return $next($request);
        }

        abort(403, 'You don\'t Have a permission to Access this page.');
    }

    private function normalizePermissions(array $permissions): array
    {
        $normalized = [];

        foreach ($permissions as $permission) {
            foreach (preg_split('/[|,]/', (string) $permission) ?: [] as $item) {
                $item = trim($item);

                if ($item !== '') {
                    $normalized[] = $item;
                }
            }
        }

        return array_values(array_unique($normalized));
    }
}
