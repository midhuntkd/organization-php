<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
        } else {
            // organization admin login 
            //$user = User::where('email', $request->email)->first();
        }

        // Validate user
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        // If organization admin
        if ($user->hasRole('organization-admin')) {
            Auth::login($user);
            return redirect()->route('admin.dashboard');
        }

        // If member, check if the organization matches
        if ($user->hasRole('member') && $user->organization_id == $organizationId) {
            Auth::login($user);
            return redirect()->route('member.dashboard');
        }

        // Default to login failure
        throw ValidationException::withMessages([
            'email' => ['You are not authorized to log in as a member for this organization.'],
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
