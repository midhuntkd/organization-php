<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class OrgNewPasswordController extends Controller
{
    /**
     * Display the org-scoped password reset view.
     */
    public function create(Request $request, Organization $organization, string $token): View
    {
        return view('auth.custom_reset_password', [
            'request' => $request,
            'organization' => $organization,
            'token' => $token,
        ]);
    }

    /**
     * Handle an incoming new password request, org-scoped.
     */
    public function store(Request $request, Organization $organization): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Ensure the email belongs to this organization before attempting reset
        $user = User::query()
            ->where('email', $request->email)
            ->where('organization_id', $organization->id)
            ->first();

        if (! $user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __('No account found for this email in this organization.')]);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                    'password_changed' => true,
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('member_login', $organization->slug)->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}

