<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class CustomAuthenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if ($request->expectsJson()) {
            return null;
        }

        // 🔹 If request is inside organization slug
        if ($request->route('organization')) {
            $organization = $request->route('organization');
            $slug = is_string($organization) ? $organization : ($organization->slug ?? null);

            if ($slug) {
                return route('member_login', ['organization' => $slug]);
            }
        }

        // 🔹 For super admin routes
        if ($request->is('super-admin/*')) {
            return route('superadmin.login');
        }

        // 🔹 Optional fallback
        return route('superadmin.login'); 
    }

    /**
     * Public helper so you can safely call it in bootstrap/app.php
     */
    public function getRedirectUrl($request)
    {
        return $this->redirectTo($request);
    }

}
