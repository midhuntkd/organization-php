<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipBenefit;
use App\Models\Organization;
use Illuminate\Http\Request;

class MembershipBenefitController extends Controller
{
    public function index(Organization $organization)
    {
        $benefits = MembershipBenefit::where('organization_id', $organization->id)->get();
        return view('admin.memberships.benefits.index', compact('benefits', 'organization'));
    }

    public function create(Organization $organization)
    {
        return view('admin.memberships.benefits.create', compact('organization'));
    }

    public function store(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $validated['organization_id'] = $organization->id;
        MembershipBenefit::create($validated);

        return redirect()->route('orgadmin.membership_benefits.index', $organization->slug)
            ->with('status', 'Benefit created successfully.');
    }

    public function edit(Organization $organization, string $benefit)
    {
        $benefitInfo = MembershipBenefit::find($benefit);
        abort_unless($benefitInfo->organization_id === $organization->id, 403);
        return view('admin.memberships.benefits.edit', compact('organization', 'benefitInfo'));
    }

    public function update(Request $request, Organization $organization, string $benefit)
    {

        $benefitInfo = MembershipBenefit::find($benefit);
        abort_unless($benefitInfo->organization_id === $organization->id, 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $benefitInfo->update($validated);
        return redirect()->route('orgadmin.membership_benefits.index', $organization->slug)
            ->with('status', 'Benefit updated.');
    }

    public function destroy(Organization $organization, string $benefit)
    {
        $benefitInfo = MembershipBenefit::find($benefit);
        abort_unless($benefitInfo->organization_id === $organization->id, 403);
        $benefitInfo->delete();
        return back()->with('status', 'Benefit deleted.');
    }
}