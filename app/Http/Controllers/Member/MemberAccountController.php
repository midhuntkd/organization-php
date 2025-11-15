<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Membership;
use App\Models\User as UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MemberAccountController extends Controller
{
    /**
     * Show the "change password" screen if password_changed == false.
     */
    public function showChangePassword(Organization $organization)
    {
        $user = Auth::user();

        // optional: if already changed, go to dashboard
        if ($user->password_changed) {
            return redirect()->route('member.dashboard', $organization->slug);
        }

        return view('member.auth.password_change', compact('organization', 'user'));
    }

    /**
     * Handle password update; mark password_changed = true, then go to dashboard.
     */
    public function updatePassword(Request $request, Organization $organization)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password'      => ['required'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            // expects input name="password_confirmation"
        ]);

        // verify current password matches
        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.'])
                ->withInput();
        }

        // update
        $user->password = Hash::make($validated['password']);
        $user->password_changed = true;
        $user->save();

        // (Optional) logout other devices
        // Auth::logoutOtherDevices($validated['password']);

        return redirect()
            ->route('member.dashboard', $organization->slug)
            ->with('status', 'Password updated successfully.');
    }

    /**
     * Member dashboard: show current membership info + rules + benefits
     */
    public function dashboard(Organization $organization)
    {
        $user = Auth::user();

        $currentMembership = $user->membership;
        if (
            !$user->membership_id ||
            !$currentMembership ||
            $currentMembership->organization_id !== $organization->id
        ) {
            $defaultMembership = Membership::where('organization_id', $organization->id)
                ->where('status', 'active')
                ->where('is_default', true)
                ->first();

            if ($defaultMembership) {
                $user->membership_id = $defaultMembership->id;
                if (empty($user->membership_code)) {
                    $user->membership_code = UserModel::nextMembershipCode($organization, $defaultMembership);
                }
                $user->save();
                $user->unsetRelation('membership');
            }
        }

        // eager-load membership with category, rules, benefits
        $user->load([
            'membership.rules'    => fn($q) => $q->orderBy('id'),      // or orderBy('created_at','desc')
            'membership.benefits' => fn($q) => $q->orderBy('id'),
            'details',
        ]);

        // If no membership yet, you might show a message or redirect
        $membership = $user->membership;
        //$category   = $membership?->category;
        $rules      = $membership?->rules ?? collect();
        $benefits   = $membership?->benefits ?? collect();

        return view('member.dashboard', compact(
            'organization',
            'user',
            'membership',
            //'category',
            'rules',
            'benefits'
        ));
    }
}
