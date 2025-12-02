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
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Permission;

class MemberController extends Controller
{
    public function showMemberList(Organization $organization)
    {
        $members = User::role('member')
            ->where(['organization_id' => $organization->id, 'approved' => 1])
            ->with('organization')
            ->get();

        return view('admin.member_list_approve', compact('members', 'organization'));
    }

    public function showApprovalMemberList(Organization $organization)
    {
        $members = User::role('member')
            ->where(['organization_id' => $organization->id, 'approved' => 0])
            ->with('organization')
            ->get();

        return view('admin.member_list', compact('members', 'organization'));
    }

    public function permissionIndex(Organization $organization)
    {
        $members = User::role('member')
            ->where('organization_id', $organization->id)
            ->with('permissions')
            ->get();

        [$permissionOptions] = $this->getPermissionDataForUser(new User());

        return view('admin.member_permissions', compact('members', 'organization', 'permissionOptions'));
    }

    public function approve(Request $request, Organization $organization, User $user)
    {
        if (is_string($organization)) {
            $organization = Organization::where('slug', $organization)->first();
        }

        $plain = mt_rand(1000, 9999);

        $membership = Membership::where('organization_id', $organization->id)
            ->where('is_default', true)
            ->where('status', 'active')
            ->first();

        if (! $membership) {
            return back()->with('error', 'No default membership is set for this organization.');
        }

        $membershipCode = User::nextMembershipCode($organization, $membership);

        $user->update([
            'password'         => Hash::make($plain),
            'approved'         => true,
            'rejected'         => false,
            'membership_id'    => $membership->id,
            'membership_code'  => $membershipCode,
            'password_changed' => false,
            'membership_started_at' => now(),
        ]);

        $loginUrl = route('member_login', $organization->slug);
        $verifyUrl = null;

        if (! $user->hasVerifiedEmail()) {
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
            'approved' => false,
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

        [$permissionOptions, $userPermissions] = $this->getPermissionDataForUser($user);

        return view('admin.member_view', compact('user', 'organization', 'permissionOptions', 'userPermissions'));
    }

    public function update(Request $request, $organization, User $user)
    {
        if (is_string($organization)) {
            $organization = Organization::where('slug', $organization)->first();
        }

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
            'user_image' => ['nullable', 'image', 'max:4096'],
            'id_card_front' => ['nullable', 'image', 'max:5120'],
            'id_card_back' => ['nullable', 'image', 'max:5120'],
        ]);

        $updateData = [
            'name'                   => $validated['name'],
            'phone'                  => $validated['phone'],
            'country_of_residence'   => $validated['country_of_residence'],
            'verification_type'      => $validated['verification_type'],
            'verification_id_number' => $validated['verification_id_number'],
        ];

        if ($request->hasFile('user_image')) {
            if ($user->user_image && Storage::disk('public')->exists($user->user_image)) {
                Storage::disk('public')->delete($user->user_image);
            }
            $updateData['user_image'] = $request->file('user_image')->store('user_images', 'public');
        }

        if ($request->hasFile('id_card_front')) {
            if ($user->id_card_front && Storage::disk('public')->exists($user->id_card_front)) {
                Storage::disk('public')->delete($user->id_card_front);
            }
            $updateData['id_card_front'] = $request->file('id_card_front')->store('verifications', 'public');
        }

        if ($request->hasFile('id_card_back')) {
            if ($user->id_card_back && Storage::disk('public')->exists($user->id_card_back)) {
                Storage::disk('public')->delete($user->id_card_back);
            }
            $updateData['id_card_back'] = $request->file('id_card_back')->store('verifications', 'public');
        }

        $user->update($updateData);

        return back()->with('status', 'Member information updated.');
    }

    public function updatePermissions(Request $request, Organization $organization, User $user)
    {
        if ($user->organization_id !== $organization->id) {
            abort(403);
        }

        $requested = $request->input('permissions', []);

        $allowed = collect($this->permissionOptions())->pluck('key')->toArray();

        $safePermissions = collect($requested)
            ->filter(fn($value) => in_array($value, $allowed, true))
            ->values()
            ->all();

        foreach ($safePermissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission],
                ['guard_name' => 'web']
            );
        }

        $user->syncPermissions($safePermissions);

        return back()->with('status', 'Permissions updated for this member.');
    }

    protected function getPermissionDataForUser(User $user): array
    {
        $options = $this->permissionOptions();
        $current = $user->getPermissionNames()->toArray();

        return [$options, $current];
    }

    protected function permissionOptions(): array
    {
        return [
            ['key' => 'access.members', 'label' => 'Manage Members'],
            ['key' => 'access.memberships', 'label' => 'Manage Membership Plans (plans, rules, benefits, upgrades)'],
            ['key' => 'access.payments', 'label' => 'Manage Payments'],
        ];
    }
}
