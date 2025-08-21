<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMembershipRuleRequest;
use App\Models\Membership;
use App\Models\MembershipRule;
use App\Models\Organization;
use Illuminate\Http\Request;

class MembershipRuleController extends Controller
{
    public function index(Organization $organization, Membership $membership)
    {
        abort_unless($membership->organization_id === $organization->id, 403);
        $rules = $membership->rules()->latest()->get();
        return view('admin.memberships.rules.index', compact('organization', 'membership', 'rules'));
    }

    public function create(Organization $organization, Membership $membership)
    {
        abort_unless($membership->organization_id === $organization->id, 403);
        return view('admin.memberships.rules.create', compact('organization', 'membership'));
    }

    public function store(StoreMembershipRuleRequest $request, Organization $organization, Membership $membership)
    {
        abort_unless($membership->organization_id === $organization->id, 403);
        $membership->rules()->create($request->validated());
        return redirect()->route('orgadmin.memberships.rules.index', [$organization->slug, $membership->id])
            ->with('status', 'Rule added.');
    }

    public function edit(Organization $organization, MembershipRule $rule)
    {
        abort_unless($rule->membership->organization_id === $organization->id, 403);
        $membership = $rule->membership;
        return view('admin.memberships.rules.edit', compact('organization', 'membership', 'rule'));
    }

    public function update(StoreMembershipRuleRequest $request, Organization $organization, MembershipRule $rule)
    {
        abort_unless($rule->membership->organization_id === $organization->id, 403);
        $rule->update($request->validated());
        return redirect()->route('orgadmin.memberships.rules.index', [$organization->slug, $rule->membership_id])
            ->with('status', 'Rule updated.');
    }

    public function destroy(Organization $organization, MembershipRule $rule)
    {
        abort_unless($rule->membership->organization_id === $organization->id, 403);
        $rule->delete();
        return back()->with('status', 'Rule deleted.');
    }
}
