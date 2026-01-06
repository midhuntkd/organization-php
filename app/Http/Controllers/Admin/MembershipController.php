<?php

namespace App\Http\Controllers\Admin;

use App\Models\Membership;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMembershipRequest;
use App\Models\MembershipBenefit;
use App\Models\MembershipCategory;
use App\Models\MembershipRule;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index(Organization $organization)
    {
        $memberships = Membership::where('organization_id', $organization->id)
            ->paginate(20);
        return view('admin.memberships.index', compact('organization', 'memberships'));
    }

    public function create(Organization $organization)
    {
        // Only ACTIVE categories for select
        $rules = MembershipRule::where('organization_id', $organization->id)->get();
        $benefits = MembershipBenefit::where('organization_id', $organization->id)->get();
        // $categories = MembershipCategory::where('organization_id', $organization->id)
        //     ->active()->orderBy('name')->get();
        return view('admin.memberships.create', compact('organization',  'rules', 'benefits'));
    }

    public function store(StoreMembershipRequest $request, Organization $organization)
    {
        $data = $request->validated();
        // Ensure the selected category belongs to this org & is active
        
        // $category = MembershipCategory::where('organization_id', $organization->id)
        //     ->active()->findOrFail($data['membership_category_id']);

        $data['organization_id'] = $organization->id;

        if (!empty($data['is_default']) && $data['is_default']) {
            Membership::where('organization_id', $organization->id)->update(['is_default' => false]);
        }

        $membership = Membership::create($data);

        $membership->rules()->sync($data['rule_ids'] ?? []);
        $membership->benefits()->sync($data['benefit_ids'] ?? []);



        return redirect()->route('orgadmin.memberships.index', $organization->slug)
            ->with('status', 'Membership created.');
    }

    public function edit(Organization $organization, Membership $membership)
    {
        $rules = MembershipRule::where('organization_id', $organization->id)->get();
        $benefits = MembershipBenefit::where('organization_id', $organization->id)->get();
        $categories = [];
        //abort_unless($membership->organization_id === $organization->id, 403);
        // $categories = MembershipCategory::where('organization_id', $organization->id)
        //     ->active()->orderBy('name')->get();
        return view('admin.memberships.edit', compact('organization', 'membership', 'categories', 'rules', 'benefits'));
    }

    public function update(StoreMembershipRequest $request, Organization $organization, Membership $membership)
    {
        //abort_unless($membership->organization_id === $organization->id, 403);
        $data = $request->validated();
        $oldPrefix = trim((string) $membership->prefix) ?: 'CAT';

        // Re-validate category belongs to org & active
        // $category = MembershipCategory::where('organization_id', $organization->id)
        //     ->active()->findOrFail($data['membership_category_id']);

        if (!empty($data['is_default']) && $data['is_default']) {
            Membership::where('organization_id', $organization->id)->where('id', '!=', $membership->id)->update(['is_default' => false]);
        }

        $membership->update($data);

        $newPrefix = trim((string) $membership->prefix) ?: 'CAT';
        if ($oldPrefix !== $newPrefix) {
            $orgPrefix = trim((string) $organization->org_prefix) ?: 'ORG';
            $pattern = '/^' . preg_quote($orgPrefix, '/') . '-' . preg_quote($oldPrefix, '/') . '(\d+)$/';

            User::query()
                ->where('organization_id', $organization->id)
                ->where('membership_id', $membership->id)
                ->whereNotNull('membership_code')
                ->orderBy('id')
                ->chunkById(200, function ($users) use ($orgPrefix, $newPrefix, $pattern, $organization, $membership) {
                    foreach ($users as $user) {
                        $code = (string) $user->membership_code;
                        if (preg_match($pattern, $code, $m)) {
                            $candidate = $orgPrefix . '-' . $newPrefix . $m[1];
                            $exists = User::query()
                                ->where('organization_id', $organization->id)
                                ->where('membership_code', $candidate)
                                ->where('id', '<>', $user->id)
                                ->exists();
                            $user->membership_code = $exists
                                ? User::nextMembershipCode($organization, $membership)
                                : $candidate;
                            $user->save();
                        }
                    }
                });
        }

        $membership->rules()->sync($request->rule_ids ?? []);
        $membership->benefits()->sync($request->benefit_ids ?? []);

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
