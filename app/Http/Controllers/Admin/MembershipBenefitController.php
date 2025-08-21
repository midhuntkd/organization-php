<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMembershipBenefitRequest;
use App\Models\Membership;
use App\Models\MembershipBenefit;
use App\Models\Organization;
use Illuminate\Http\Request;

class MembershipBenefitController extends Controller
{
    public function index(Organization $organization, Membership $membership)
    {
        abort_unless($membership->organization_id === $organization->id, 403);
        $benefits = $membership->benefits()->latest()->get();
        return view('admin.memberships.benefits.index', compact('organization', 'membership', 'benefits'));
    }

    public function create(Organization $organization, Membership $membership)
    {
        abort_unless($membership->organization_id === $organization->id, 403);
        return view('admin.memberships.benefits.create', compact('organization', 'membership'));
    }

    public function store(StoreMembershipBenefitRequest $request, Organization $organization, Membership $membership)
    {
        abort_unless($membership->organization_id === $organization->id, 403);
        $membership->benefits()->create($request->validated());
        return redirect()->route('orgadmin.memberships.benefits.index', [$organization->slug, $membership->id])
            ->with('status', 'Benefit added.');
    }

    public function edit(Organization $organization, MembershipBenefit $benefit)
    {
        abort_unless($benefit->membership->organization_id === $organization->id, 403);
        $membership = $benefit->membership;
        return view('admin.memberships.benefits.edit', compact('organization', 'membership', 'benefit'));
    }

    public function update(StoreMembershipBenefitRequest $request, Organization $organization, MembershipBenefit $benefit)
    {
        abort_unless($benefit->membership->organization_id === $organization->id, 403);
        $benefit->update($request->validated());
        return redirect()->route('orgadmin.memberships.benefits.index', [$organization->slug, $benefit->membership_id])
            ->with('status', 'Benefit updated.');
    }

    public function destroy(Organization $organization, MembershipBenefit $benefit)
    {
        abort_unless($benefit->membership->organization_id === $organization->id, 403);
        $benefit->delete();
        return back()->with('status', 'Benefit deleted.');
    }
}
