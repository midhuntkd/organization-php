<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;


class CustomLoginController extends Controller
{
    public function showLoginForm()
    {
        $userType = array('user_type'=> 'admin');
        return view('auth.custom_login', compact('userType'));
    }

    public function showMemberLoginForm(Organization $organization)
    {
        $userType = array('user_type' => 'member');
        return view('auth.custom_login', compact('userType', 'organization'));
    }

    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            //'organization_id' => 'nullable|exists:organizations,id',  // Only for members
        ]);

        $user = User::where('email', $request->email)->first();
        // Validate user
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        // If super admin
        if ($user->hasRole('super-admin')) {
            Auth::login($user);
            return redirect()->route('admin.dashboard');
        }

        
        // Default to login failure
        throw ValidationException::withMessages([
            'email' => ['You are not authorized to log in as a superadmin.'],
        ]);
    }

    public function organizationLogin(Request $request, Organization $organization)
    {
        $organizationId = $organization ? $organization->id : null;
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            //'organization_id' => 'nullable|exists:organizations,id',  // Only for members
        ]);

        // Check for super admin role login
        if ($request->has('user_type') && $request->user_type === 'member') {
            // Member login with org scope
            $user = User::where('email', $request->email)
                ->where('organization_id', $organizationId)
                ->first();
        } 
        
        if(!$user) {
            throw ValidationException::withMessages([
                'email' => ['invalid email id specified.'],
            ]);
        }

        // Validate user
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        // 1) Email not verified
        if (! $user->hasVerifiedEmail()) {
            // Optional: re-send link automatically:

            $user->sendEmailVerificationNotification();

            throw ValidationException::withMessages([
                'email' => __('Please verify your email address. We have sent you a verification link.'),
            ]);
        }

        // 2) Not approved by admin
        if (! $user->approved) {
            throw ValidationException::withMessages([
                'email' => __('Your account is pending admin approval.'),
            ]);
        }

        Auth::login($user, $request->boolean('remember'));

        // If organization admin
        if ($user->hasRole('organization-admin')) {
            return redirect()->route('orgadmin.members', $organization->slug);
        }

        // If member, check if the organization matches
        if ($user->hasRole('member') && $user->organization_id == $organizationId) {

            // (Optional) force password change on first login
            if (! $user->password_changed) {
                return redirect()->route('member.password.change', $organization->slug)
                    ->with('status', __('Please set a new password to continue.'));
            }

            return redirect()->route('member.dashboard', $organization->slug);
        }

        // Default to login failure
        throw ValidationException::withMessages([
            'email' => ['You are not authorized to log in as a member for this organization.'],
        ]);
    }

    public function logout(Organization $organization)
    {
        Auth::logout();
        return redirect()->route('member_login', ['organization' => $organization->slug])
            ->with('status', __('You have been logged out successfully.')); 
    }


    public function check(Request $request, ?Organization $organization = null)
    {
        // Basic validation
        $validator = Validator::make($request->all(), [
            'email'      => ['required', 'email'],
            'user_type'  => ['required', 'in:member,admin'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok'      => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $email     = $request->input('email');
        $userType  = $request->input('user_type'); // 'member' or 'admin'

        // Build query
        $query = User::query()->where('email', $email);

        // For members, scope to current organization
        if ($userType === 'member') {
            if (! $organization) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Organization context missing.',
                ], 422);
            }
            $query->where('organization_id', $organization->id);
        }
        $user = $query->first();

        if (! $user) {
            return response()->json([
                'ok'      => false,
                'message' => 'No account found for this email in the selected organization.',
            ], 404);
        }

        // Email verified?
        if (! $user->hasVerifiedEmail()) {
            return response()->json([
                'ok'      => false,
                'message' => 'Email not verified. Please verify your email first.',
                'unverified' => true,
            ], 422);
        }

        // Admin approved?
        if (! $user->approved) {
            return response()->json([
                'ok'      => false,
                'message' => 'Your account is pending admin approval.',
            ], 422);
        }

        return response()->json([
            'ok'      => true,
            'message' => 'Email verified and approved. You can log in now.',
        ]);
    }
}
