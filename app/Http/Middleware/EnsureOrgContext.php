<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrgContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $organization = $request->route('organization');

        // If it's still string (slug), resolve manually
        if (is_string($organization)) {
            $organization = Organization::where('slug', $organization)->first();
        }

        if (! $organization) {
            abort(404, 'Organization not found.');
        }
        //$orgInfo = Organization::fromSlugOrFail($organization);

        $user = $request->user();

        // if (! $organization || ! $user || $user->organization_id !== $organization->id) {
        //     abort(403, 'You are not allowed to access this organization.');
        // }

        return $next($request);
    }
}
