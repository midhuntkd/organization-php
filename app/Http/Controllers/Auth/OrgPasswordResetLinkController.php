<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class OrgPasswordResetLinkController extends Controller
{
    /**
     * Show org-scoped forgot password view.
     */
    public function create(Organization $organization): View
    {
        return view('auth.custom_forgot_password', compact('organization'));
    }

    /**
     * Send reset link if email belongs to this organization.
     */
    public function store(Request $request, Organization $organization): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->input('email');

        // Ensure the email exists within this organization
        $user = User::query()
            ->where('email', $email)
            ->where('organization_id', $organization->id)
            ->first();

        if (! $user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __('No account found for this email in this organization.')]);
        }

        // Create token for this specific user and send notification directly
        $token = Password::broker()->createToken($user);
        $user->sendPasswordResetNotification($token);

        return back()->with('status', __(\Illuminate\Support\Facades\Password::RESET_LINK_SENT));
    }
}
