<?php

namespace App\Http\Controllers\Admin;

use App\Models\Membership;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMembershipRequest;
use App\Models\MembershipCategory;
use App\Models\Organization;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index(Organization $organization)
    {
        $memberships = Membership::with('category')
            ->where('organization_id', $organization->id)
            ->paginate(20);
        return view('admin.memberships.index', compact('organization', 'memberships'));
    }

    public function create(Organization $organization)
    {
        // Only ACTIVE categories for select
        $categories = MembershipCategory::where('organization_id', $organization->id)
            ->active()->orderBy('name')->get();
        return view('admin.memberships.create', compact('organization', 'categories'));
    }

    public function store(StoreMembershipRequest $request, Organization $organization)
    {
        $data = $request->validated();
        // Ensure the selected category belongs to this org & is active
        $category = MembershipCategory::where('organization_id', $organization->id)
            ->active()->findOrFail($data['membership_category_id']);

        $data['organization_id'] = $organization->id;

        if (!empty($data['is_default']) && $data['is_default']) {
            Membership::where('organization_id', $organization->id)->update(['is_default' => false]);
        }

        Membership::create($data);

        return redirect()->route('orgadmin.memberships.index', $organization->slug)
            ->with('status', 'Membership created.');
    }

    public function edit(Organization $organization, Membership $membership)
    {
        //abort_unless($membership->organization_id === $organization->id, 403);
        $categories = MembershipCategory::where('organization_id', $organization->id)
            ->active()->orderBy('name')->get();
        return view('admin.memberships.edit', compact('organization', 'membership', 'categories'));
    }

    public function update(StoreMembershipRequest $request, Organization $organization, Membership $membership)
    {
        //abort_unless($membership->organization_id === $organization->id, 403);
        $data = $request->validated();

        // Re-validate category belongs to org & active
        $category = MembershipCategory::where('organization_id', $organization->id)
            ->active()->findOrFail($data['membership_category_id']);

        if (!empty($data['is_default']) && $data['is_default']) {
            Membership::where('organization_id', $organization->id)->where('id', '!=', $membership->id)->update(['is_default' => false]);
        }

        $membership->update($data);

        return redirect()->route('orgadmin.memberships.index', $organization->slug)
            ->with('status', 'Membership updated.');
    }

    public function destroy(Organization $organization, Membership $membership)
    {
        //abort_unless($membership->organization_id === $organization->id, 403);
        $membership->delete();
        return back()->with('status', 'Membership deleted.');
    }
}
