<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && ! Auth::user()->password_changed) {
            if (! $request->routeIs('password.change', 'password.change.submit')) {
                return redirect()->route('member.password.change')
                    ->with('status', 'Please set a new password to continue.');
            }
        }
        return $next($request);
    }
}
