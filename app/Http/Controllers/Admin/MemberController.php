<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\MemberApprovedMail;
use App\Mail\MemberRejectedMail;
use App\Models\MemberRejection;
use App\Models\Membership;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;

class MemberController extends Controller
{
    public function showMemberList(Organization $organization)
    {
        $members = User::role('member')->where(['organization_id' => $organization->id, 'approved' => 1])->with('organization')->get();
        return view('admin.member_list_approve', compact('members', 'organization'));
    }

    public function showApprovalMemberList(Organization $organization)
    {
        $members = User::role('member')->where(['organization_id'=> $organization->id, 'approved'=>0])->with('organization')->get();
        return view('admin.member_list', compact('members', 'organization'));
    }
    
    public function approve(Request $request, Organization $organization, User $user)
    {

        if (is_string($organization)) {
            $organization = Organization::where('slug', $organization)->first();
        }

        //abort_unless($user->organization_id === $organization->id, 403);
        $plain = mt_rand(1000, 9999);

        // 1) Get default membership for this org
        $membership = Membership::where('organization_id', $organization->id)
            ->where('is_default', true)
            ->where('status', 'active')
            ->first();
        if (! $membership) {
            // $membership = Membership::with('category')
            //     ->where('organization_id', $organization->id)
            //     ->where('status', 'active')
            //     ->first();
            
            return back()->with('error', 'No default membership is set for this organization.');
             
        }

        // 2) Generate the membership code
        //$category = $membership->category; // must exist
        // if (! $category) {
        //     return back()->with('error', 'The default membership has no category.');
        // }

        $membershipCode = User::nextMembershipCode($organization, $membership);

        $user->update([
            'password'         => Hash::make($plain),
            'approved'         => true,
            'rejected'         => false,
            'membership_id'    => $membership->id,
            'membership_code'  => $membershipCode,
            'password_changed' => false,  // they must update on first login
        ]);

        $loginUrl = route('member_login', $organization->slug);
        $verifyUrl = null;

        if (! $user->hasVerifiedEmail()) {
            // If you use your custom verify route from earlier:
            $verifyUrl = URL::temporarySignedRoute(
                'custom.verification.verify',
                now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id'   => $user->getKey(),
                    'hash' => sha1($user->getEmailForVerification()),
                ]
            );
        }
        Mail::to($user->email)->send(
            new MemberApprovedMail($user, $organization, $plain, $loginUrl, $verifyUrl)
        );

        return back()->with('status', 'Member approved and email sent with login details.');
    }

    public function reject(Request $request, $organization, User $user)
    {

        if (is_string($organization)) {
            $organization = Organization::where('slug', $organization)->first();
        }

        //abort_unless($user->organization_id === $organization->id, 403);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
        ]);

        MemberRejection::create([
            'user_id'     => $user->id,
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'rejected_by' => Auth::id(),
        ]);

        $user->update([
            'rejected' => true,
            'approved' => false, // ensure not approved
        ]);

        Mail::to($user->email)->send(
            new MemberRejectedMail($user, $organization, $validated['title'], $validated['description'] ?? null)
        );

        return back()->with('status', 'Member rejected with reason saved.');
    }

    public function view($organization, User $user)
    {
        if (is_string($organization)) {
            $organization = Organization::where('slug', $organization)->first();
        }
        //abort_unless($user->organization_id === $organization->id, 403);

        return view('admin.member_view', compact('user', 'organization'));
    }

    public function update(Request $request, $organization, User $user)
    {

        if (is_string($organization)) {
            $organization = Organization::where('slug', $organization)->first();
        }

        //abort_unless($user->organization_id === $organization->id, 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'country_of_residence' => 'required|in:India,UAE',
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

        $user->update([
            'name'                   => $validated['name'],
            'phone'                  => $validated['phone'],
            'country_of_residence'   => $validated['country_of_residence'],
            'verification_type'      => $validated['verification_type'],
            'verification_id_number' => $validated['verification_id_number'],
        ]);



        return back()->with('status', 'Member information updated.');
    }

}
