<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomRegisterController extends Controller
{
    public function showRegisterForm(Organization $organization)
    {
        //$organization = Organization::where('is_default', true)->first();
        //return view('auth.custom-register', compact('organization'));
        return view('auth.custom-register', compact('organization'));
    }

    public function register(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'verification_image' => 'required|image|max:2048',
            'verification_type'      => 'required|in:aadhaar,emirates_id',
            'verification_id_number' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $digitsOnly = preg_replace('/\D/', '', $value);

                    if ($request->verification_type === 'aadhaar' && strlen($digitsOnly) !== 12) {
                        $fail('Aadhaar number must be exactly 12 digits.');
                    }

                    if ($request->verification_type === 'emirates_id' && strlen($digitsOnly) !== 15) {
                        $fail('Emirates ID must be exactly 15 digits.');
                    }
                }
            ],
        ]);

        $imagePath = $request->file('verification_image')->store('verifications', 'public');
        $validated['organization_id'] = $organization->id; // Set default organization ID
        $validated['verification_image'] = $imagePath;
        // echo "<pre>";
        // print_r($validated); exit;
        $user = User::create([
            ...$validated,
            'password' => Hash::make(Str::random(10)), // temporary password
        ]);

        $user->assignRole('member');
        $user->sendEmailVerificationNotification();

        return redirect()->route('member_login', $organization->slug)->with('status', 'Verify your email first.');
    }
}
