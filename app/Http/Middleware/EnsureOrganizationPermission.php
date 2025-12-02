<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureOrganizationPermission
{
    /**
     * Allow access if the user is an organization admin / super admin
     * or holds at least one of the required permissions.
     *
     * Usage: ->middleware('org.permission:access.members|access.memberships')
     */
    public function handle(Request $request, Closure $next, string $permissions)
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        if ($user->hasAnyRole(['super-admin', 'organization-admin'])) {
            return $next($request);
        }

        $permissionList = array_filter(explode('|', $permissions));

        if ($user->hasAnyPermission($permissionList)) {
            return $next($request);
        }

        abort(403);
    }
}
