<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmailController extends Controller
{
    public function __invoke(Request $request, $id, $hash)
    {
        // Find the user referenced by the URL
        $user = User::findOrFail($id);

        // Validate the hash (same check Laravel does internally)
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid verification link.');
        }

        // Already verified? just redirect to member login with their org
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('member_login', $user->organization->slug)
                ->with('status', 'Email already verified.');
        }

        // Mark as verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // OPTIONAL: you can set a flash flag to show a success message on the login page
        return redirect()->route('member_login', $user->organization->slug)
            ->with('status', 'Email verified. Please log in.');
    }
}