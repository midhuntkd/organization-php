<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlanUpgradeRequest;
use App\Models\Organization;
use App\Models\User;
use App\Models\MembershipPaymentLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipPlanUpgradeController extends Controller
{
    public function index(Organization $organization)
    {
        $requests = MembershipPlanUpgradeRequest::query()
            ->with(['user', 'membership', 'currentMembership'])
            ->whereHas('user', fn($q) => $q->where('organization_id', $organization->id))
            ->where('status', 'requested')
            ->latest()
            ->get();

        return view('admin.memberships.upgrades.index', compact('organization', 'requests'));
    }

    public function show(Organization $organization, MembershipPlanUpgradeRequest $upgradeRequest)
    {
        $upgradeRequest->load([
            'user.details',
            'currentMembership',
            'membership.rules' => fn($q) => $q->orderBy('id'),
            'membership.benefits' => fn($q) => $q->orderBy('id'),
        ]);

        abort_if($upgradeRequest->user?->organization_id !== $organization->id, 404);

        return view('admin.memberships.upgrades.show', compact('organization', 'upgradeRequest'));
    }

    public function approve(Request $request, Organization $organization, MembershipPlanUpgradeRequest $upgradeRequest)
    {
        $user = $upgradeRequest->user;

        if (! $user || $user->organization_id !== $organization->id) {
            abort(404);
        }

        if (! in_array($upgradeRequest->status, ['requested', 'hold'], true)) {
            return back()->with('error', 'This request has already been processed.');
        }

        $targetMembership = $upgradeRequest->membership;

        if (! $targetMembership || $targetMembership->organization_id !== $organization->id) {
            return back()->with('error', 'Requested membership is no longer available for this organization.');
        }

        if ($targetMembership->status !== 'active') {
            return back()->with('error', 'Requested membership is not active.');
        }

        // Assign the new membership and regenerate the code when moving plans or missing a code.
        if ($user->membership_id !== $targetMembership->id || empty($user->membership_code)) {
            $user->membership_id = $targetMembership->id;
            $user->membership_code = User::nextMembershipCode($organization, $targetMembership);
            $user->membership_started_at = now();
        }

        $user->save();

        $upgradeRequest->status = 'approved';
        $upgradeRequest->approved_by = Auth::id();
        $upgradeRequest->save();

        // Joining fee charge
        \App\Models\MembershipPaymentLedger::create([
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'membership_id' => $targetMembership->id,
            'entry_type' => 'charge',
            'reason' => 'joiningfee',
            'amount' => $targetMembership->joining_fee,
            'description' => 'Joining fee for upgrade approval',
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'payment_date' => now(),
        ]);

        MembershipPlanUpgradeRequest::where('user_id', $user->id)
            ->where('id', '<>', $upgradeRequest->id)
            ->whereIn('status', ['requested', 'hold'])
            ->update([
                'status' => 'rejected',
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'reject_reason' => 'Superseded by another approval.',
            ]);

        return back()->with('status', 'Membership upgrade approved and applied to the user.');
    }

    public function reject(Request $request, Organization $organization, MembershipPlanUpgradeRequest $upgradeRequest)
    {
        $user = $upgradeRequest->user;

        if (! $user || $user->organization_id !== $organization->id) {
            abort(404);
        }

        if ($upgradeRequest->status !== 'requested' && $upgradeRequest->status !== 'hold') {
            return back()->with('error', 'This request has already been processed.');
        }

        $data = $request->validate([
            'reason' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $combinedReason = $data['reason'];
        if (! empty($data['description'])) {
            $combinedReason .= "\n\n" . $data['description'];
        }

        $upgradeRequest->update([
            'status' => 'rejected',
            'rejected_by' => Auth::id(),
            'rejected_at' => now(),
            'reject_reason' => $combinedReason,
        ]);

        return redirect()
            ->route('orgadmin.membership_upgrades.show', [$organization->slug, $upgradeRequest->id])
            ->with('status', 'Upgrade request rejected.');
    }
}
