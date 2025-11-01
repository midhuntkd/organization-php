<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipRule;
use App\Models\Organization;
use Illuminate\Http\Request;

class MembershipRuleController extends Controller
{
    public function index(Organization $organization)
    {
        $rules = MembershipRule::where('organization_id', $organization->id)->get();
        return view('admin.memberships.rules.index', compact('rules', 'organization'));
    }

    public function create(Organization $organization)
    {
        return view('admin.memberships.rules.create', compact('organization'));
    }

    public function store(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $validated['organization_id'] = $organization->id;
        MembershipRule::create($validated);

        return redirect()->route('orgadmin.membership_rules.index', $organization->slug)
            ->with('status', 'Rule created successfully.');
    }

    public function edit(Organization $organization, string $rule)
    {
        $mebershipRule = MembershipRule::find($rule);
        abort_unless($mebershipRule->organization_id === $organization->id, 403);
        return view('admin.memberships.rules.edit', compact('organization', 'mebershipRule'));
    }

    public function update(Request $request, Organization $organization, string $rule)
    {
        $mebershipRule = MembershipRule::find($rule);
        abort_unless($mebershipRule->organization_id === $organization->id, 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $mebershipRule->update($validated);
        return redirect()->route('orgadmin.membership_rules.index', $organization->slug)
            ->with('status', 'Rule updated.');
    }

    public function destroy(Organization $organization, string $rule)
    {
        $mebershipRule = MembershipRule::find($rule);
        abort_unless($mebershipRule->organization_id === $organization->id, 403);
        $mebershipRule->delete();
        return back()->with('status', 'Rule deleted.');
    }
}