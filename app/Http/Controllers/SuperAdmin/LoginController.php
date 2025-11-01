<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('superadmin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string','min:6'],
        ]);

        // Login only users with role "super-admin"
        if (Auth::attempt(array_merge($credentials, ['organization_id' => null]), $request->boolean('remember'))) {
            $user = Auth::user();

            if (!$user->hasRole('super-admin')) {
                Auth::logout();
                return back()->withErrors(['email' => 'Unauthorized user']);
            }

            return redirect()->route('superadmin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('superadmin.login');
    }

    public function check(Request $request)
    {
        // Basic validation
        $validator = Validator::make($request->all(), [
            'email'      => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok'      => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $email     = $request->input('email');

        // Build query
        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            return response()->json([
                'ok'      => false,
                'message' => 'No account found for this email',
            ], 404);
        }

        if (!$user->hasRole('super-admin')) {
            return response()->json([
                'ok'      => false,
                'message' => 'Not a super admin account',
            ], 403);
        }

        return response()->json([
            'ok'      => true,
            'message' => 'Email verified and approved. You can log in now.',
        ]);
    }
}
