<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Membership;
use App\Models\MembershipPlanUpgradeRequest;
use App\Models\MembershipPaymentLedger;
use App\Models\User as UserModel;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
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
                if (empty($user->membership_started_at)) {
                    $user->membership_started_at = now();
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

        $this->ensureChargesUpToDate($user, $membership, $organization);
        $wallet = $this->buildWalletData($user, $organization);

        $pendingUpgradeRequest = $this->getPendingUpgradeRequest($user);

        //echo"<pre>";print_r($pendingUpgradeRequest);exit;

        $rejectedUpgradeRequest = null;
        if (! $pendingUpgradeRequest) {
            $rejectedUpgradeRequest = $this->getLatestRejectedUpgradeRequest($user);
        }

        return view('member.dashboard', compact(
            'organization',
            'user',
            'membership',
            //'category',
            'rules',
            'benefits',
            'pendingUpgradeRequest',
            'rejectedUpgradeRequest',
            'wallet'
        ));
    }

    public function transactions(Request $request, Organization $organization)
    {
        $user = Auth::user();
        $isOrgAdmin = $user->hasRole('organization-admin');

        $organizationMembers = collect();
        if ($isOrgAdmin) {
            $organizationMembers = UserModel::query()
                ->where('organization_id', $organization->id)
                ->where('approved', true)
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        $filters = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'entry_type' => $request->input('entry_type'),
            'user_id' => $request->input('user_id'),
            'payment_method' => $request->input('payment_method'),
        ];

        $query = MembershipPaymentLedger::query()
            ->with(['user', 'membership', 'approvedBy'])
            ->where('organization_id', $organization->id);

        if ($filters['start_date']) {
            $query->whereDate('payment_date', '>=', $filters['start_date']);
        }

        if ($filters['end_date']) {
            $query->whereDate('payment_date', '<=', $filters['end_date']);
        }

        if ($filters['entry_type']) {
            $query->where('entry_type', $filters['entry_type']);
        }

        if ($filters['payment_method']) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if ($isOrgAdmin) {
            if ($filters['user_id']) {
                $query->where('user_id', $filters['user_id']);
            }
        } else {
            $filters['user_id'] = $user->id;
            $query->where('user_id', $user->id);
        }

        $transactions = $query
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $paymentMethods = [
            'online_transfer' => 'Online Transfer',
            'by_hand' => 'By Hand',
            'cheque_draft' => 'Cheque / Draft',
            'other' => 'Other Method',
        ];

        $transactionsRoute = $isOrgAdmin
            ? route('orgadmin.transactions.index', $organization->slug)
            : route('member.transactions.index', $organization->slug);
        $dashboardRoute = $isOrgAdmin
            ? route('orgadmin.dashboard', $organization->slug)
            : route('member.dashboard', $organization->slug);

        return view('member.transactions.index', [
            'organization' => $organization,
            'transactions' => $transactions,
            'filters' => $filters,
            'paymentMethods' => $paymentMethods,
            'isOrgAdmin' => $isOrgAdmin,
            'organizationMembers' => $organizationMembers,
            'transactionsRoute' => $transactionsRoute,
            'dashboardRoute' => $dashboardRoute,
        ]);
    }

    /**
     * Show available memberships for upgrade.
     */
    public function showUpgradeMembership(Organization $organization)
    {
        $user = Auth::user();

        $user->load([
            'membership.rules'    => fn($q) => $q->orderBy('id'),
            'membership.benefits' => fn($q) => $q->orderBy('id'),
        ]);
        $currentMembership = $user->membership;

        $memberships = Membership::query()
            ->where('organization_id', $organization->id)
            ->where('status', 'active')
            ->when($currentMembership, fn($query) => $query->where('id', '!=', $currentMembership->id))
            ->orderBy('joining_fee')
            ->orderBy('name')
            ->get();

        $initialSelection = $memberships->first();
        if ($initialSelection) {
            $initialSelection->load([
                'rules' => fn($q) => $q->orderBy('id'),
                'benefits' => fn($q) => $q->orderBy('id'),
            ]);
        }

        $pendingRequest = $this->getPendingUpgradeRequest($user);

        $currentMembershipData = $currentMembership
            ? $this->formatMembershipPayload($currentMembership)
            : null;
        $initialMembershipData = $initialSelection
            ? $this->formatMembershipPayload($initialSelection)
            : null;
        $wallet = $this->buildWalletData($user, $organization);

        return view('member.membership_upgrade', [
            'organization' => $organization,
            'user' => $user,
            'memberships' => $memberships,
            'currentMembership' => $currentMembership,
            'currentMembershipData' => $currentMembershipData,
            'initialMembershipData' => $initialMembershipData,
            'pendingRequest' => $pendingRequest,
            'wallet' => $wallet,
        ]);
    }

    /**
     * Return the details of a membership in JSON format.
     */
    public function getMembershipDetails(Organization $organization, Membership $membership): JsonResponse
    {
        abort_unless($membership->organization_id === $organization->id, 404);

        $membership->load([
            'rules' => fn($q) => $q->orderBy('id'),
            'benefits' => fn($q) => $q->orderBy('id'),
        ]);

        return response()->json($this->formatMembershipPayload($membership));
    }

    /**
     * Create a membership upgrade request for the current user.
     */
    public function changeMembership(Request $request, Organization $organization): JsonResponse
    {
        $data = $request->validate([
            'membership_id' => ['required', 'integer', 'exists:memberships,id'],
        ]);

        $user = Auth::user();

        $existingPending = $this->getPendingUpgradeRequest($user);
        if ($existingPending) {
            return response()->json([
                'message' => 'You already have a pending request. Please wait for it to be reviewed.',
            ], 422);
        }

        $membership = Membership::query()
            ->where('organization_id', $organization->id)
            ->where('status', 'active')
            ->where('id', $data['membership_id'])
            ->firstOrFail();

        if ($user->membership_id === $membership->id) {
            return response()->json([
                'message' => 'You are already on this membership.',
            ], 422);
        }

        MembershipPlanUpgradeRequest::query()
            ->where('user_id', $user->id)
            ->where('status', 'rejected')
            ->update(['hide_status' => true]);

        MembershipPlanUpgradeRequest::create([
            'user_id' => $user->id,
            'current_membership_id' => $user->membership_id,
            'membership_id' => $membership->id,
            'status' => 'requested',
        ]);

        return response()->json([
            'message' => 'Membership upgrade request submitted successfully.',
            'redirect' => route('member.dashboard', $organization->slug),
        ]);
    }

    /**
     * Prepare membership data for JSON/JS consumption.
     */
    protected function formatMembershipPayload(Membership $membership): array
    {
        return [
            'id' => $membership->id,
            'name' => $membership->name,
            'joining_fee' => $membership->joining_fee,
            'monthly_fee' => $membership->monthly_fee,
            'rules' => $membership->rules->map(fn($rule) => [
                'id' => $rule->id,
                'title' => $rule->title,
                'description' => $rule->description,
            ])->values()->toArray(),
            'benefits' => $membership->benefits->map(fn($benefit) => [
                'id' => $benefit->id,
                'title' => $benefit->title,
                'description' => $benefit->description,
            ])->values()->toArray(),
        ];
    }

    /**
     * Return the pending upgrade request for the given user (if any).
     */
    protected function getPendingUpgradeRequest(UserModel $user): ?MembershipPlanUpgradeRequest
    {
        $pendingRequest = MembershipPlanUpgradeRequest::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['requested', 'hold'])
            ->latest()
            ->first();

        if ($pendingRequest) {
            $pendingRequest->setAppends([]);
        }

        return $pendingRequest;
    }

    protected function getLatestRejectedUpgradeRequest(UserModel $user): ?MembershipPlanUpgradeRequest
    {
        $rejectRequest = MembershipPlanUpgradeRequest::query()
            ->where('user_id', $user->id)
            ->where('status', 'rejected')
            ->where('hide_status', false)
            ->latest('rejected_at')
            ->latest() // fallback
            ->first();

        if ($rejectRequest) {
            $rejectRequest->setAppends([]);
        }

        return $rejectRequest;    
    }

    public function hideUpgradeRejection(Request $request, Organization $organization, MembershipPlanUpgradeRequest $upgradeRequest)
    {
        $user = Auth::user();

        if (! $user || $upgradeRequest->user_id !== $user->id) {
            abort(403);
        }

        if ($user->organization_id !== $organization->id) {
            abort(404);
        }

        if ($upgradeRequest->status !== 'rejected') {
            return back()->with('error', 'Only rejected requests can be hidden.');
        }

        $upgradeRequest->hide_status = true;
        $upgradeRequest->save();

        return back()->with('status', 'The rejected request has been hidden.');
    }

    protected function ensureChargesUpToDate(UserModel $user, ?Membership $membership, Organization $organization): void
    {
        if (! $membership) {
            return;
        }

        $startDate = $user->membership_started_at ?? $user->created_at ?? now();

        $lastMonthlyCharge = MembershipPaymentLedger::query()
            ->where('user_id', $user->id)
            ->where('organization_id', $organization->id)
            ->where('entry_type', 'charge')
            ->where('reason', 'monthlyfee')
            ->latest('payment_date')
            ->latest()
            ->first();

        $lastChargeDate = $lastMonthlyCharge?->payment_date ?? $startDate;
        if (! $lastChargeDate instanceof Carbon) {
            $lastChargeDate = Carbon::parse($lastChargeDate);
        }

        $today = Carbon::today();
        while ($lastChargeDate->copy()->addDays(30)->lte($today)) {
            $lastChargeDate = $lastChargeDate->copy()->addDays(30);

            MembershipPaymentLedger::create([
                'organization_id' => $organization->id,
                'user_id' => $user->id,
                'membership_id' => $membership->id,
                'entry_type' => 'charge',
                'reason' => 'monthlyfee',
                'amount' => $membership->monthly_fee,
                'description' => 'Monthly fee',
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
                'payment_date' => $lastChargeDate,
            ]);
        }
    }

    protected function buildWalletData(UserModel $user, ?Organization $organization = null): array
    {
        $organizationId = $organization?->id ?? $user->membership?->organization_id;

        $charges = MembershipPaymentLedger::query()
            ->where('user_id', $user->id)
            ->when($organizationId, fn($q) => $q->where('organization_id', $organizationId))
            ->where('entry_type', 'charge')
            ->where('status', 'approved')
            ->sum('amount');

        $paymentsApproved = MembershipPaymentLedger::query()
            ->where('user_id', $user->id)
            ->when($organizationId, fn($q) => $q->where('organization_id', $organizationId))
            ->where('entry_type', 'payment')
            ->where('status', 'approved')
            ->sum('amount');

        $pendingPayments = MembershipPaymentLedger::query()
            ->where('user_id', $user->id)
            ->when($organizationId, fn($q) => $q->where('organization_id', $organizationId))
            ->where('entry_type', 'payment')
            ->where('status', 'pending')
            ->sum('amount');

        $historyQuery = MembershipPaymentLedger::query()
            ->where('user_id', $user->id)
            ->when($organizationId, fn($q) => $q->where('organization_id', $organizationId))
            ->orderByDesc('payment_date')
            ->orderByDesc('id');

        $history = $historyQuery
            ->limit(10)
            ->get();

        return [
            'pending_balance' => max(0, $charges - $paymentsApproved),
            'pending_payments' => $pendingPayments,
            'history' => $history,
        ];
    }
}
