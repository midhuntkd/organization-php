<?php

namespace App\Http\Controllers\Admin;

use App\Models\MembershipCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMembershipCategoryRequest;
use App\Models\Organization;
use Illuminate\Http\Request;

class MembershipCategoryController extends Controller
{
    public function index(Organization $organization)
    {
        $categories = MembershipCategory::where('organization_id', $organization->id)->paginate(20);
        return view('admin.membership_categories.index', compact('organization', 'categories'));
    }

    public function create(Organization $organization)
    {
        return view('admin.membership_categories.create', compact('organization'));
    }

    public function store(StoreMembershipCategoryRequest $request, Organization $organization)
    {
        $data = $request->validated();
        $data['organization_id'] = $organization->id;

        if (!empty($data['is_default']) && $data['is_default']) {
            MembershipCategory::where('organization_id', $organization->id)->update(['is_default' => false]);
        }
        MembershipCategory::create($data);

        return redirect()->route('orgadmin.membership-categories.index', $organization->slug)
            ->with('status', 'Category created.');
    }

    public function edit(Organization $organization, MembershipCategory $membership_category)
    {
        abort_unless($membership_category->organization_id === $organization->id, 403);
        return view('admin.membership_categories.edit', compact('organization', 'membership_category'));
    }

    public function update(StoreMembershipCategoryRequest $request, Organization $organization, MembershipCategory $membership_category)
    {
        abort_unless($membership_category->organization_id === $organization->id, 403);
        $data = $request->validated();

        if (!empty($data['is_default']) && $data['is_default']) {
            MembershipCategory::where('organization_id', $organization->id)->where('id', '!=', $membership_category->id)->update(['is_default' => false]);
        }
        $membership_category->update($data);

        return redirect()->route('orgadmin.membership-categories.index', $organization->slug)
            ->with('status', 'Category updated.');
    }

    public function destroy(Organization $organization, MembershipCategory $membership_category)
    {
        abort_unless($membership_category->organization_id === $organization->id, 403);
        $membership_category->delete();
        return back()->with('status', 'Category deleted.');
    }
}
