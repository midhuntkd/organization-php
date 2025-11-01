<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Mail\AdminInviteMail;
use Spatie\Permission\Models\Role;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::with('users')->latest()->paginate(10);
        return view('superadmin.organizations.index', compact('organizations'));
    }

    public function create()
    {
        return view('superadmin.organizations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('organizations', 'name')],
            'org_prefix' => ['nullable', 'string', 'max:10'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'background_image' => ['nullable', 'image', 'max:2048'],

            // Admin info
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email', 'unique:users,email'],
            'admin_phone' => ['nullable', 'string', 'max:25'],
        ]);

        // Create org
        $orgData = $request->only(['name', 'org_prefix']);
        if ($request->hasFile('logo')) {
            $orgData['logo'] = $request->file('logo')->store('organizations', 'public');
        }
        if ($request->hasFile('background_image')) {
            $orgData['background_image'] = $request->file('background_image')->store('organizations', 'public');
        }

        $organization = Organization::create($orgData);

        // Create admin user
        $tempPassword = 'Admin@' . random_int(1000, 9999);
        $adminUser = User::create([
            'organization_id' => $organization->id,
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'phone' => $request->admin_phone,
            'password' => Hash::make($tempPassword),
            'approved' => true,
            'email_verified_at' => now(),
        ]);

        // Now manually assign
        $adminUser->email_verified_at = now();
        $adminUser->save();

        $adminUser->assignRole('organization-admin');

        

        // Send email
        Mail::to($adminUser->email)->send(new AdminInviteMail($organization, $adminUser, $tempPassword));

        return redirect()->route('superadmin.organizations.index')->with('status', 'Organization created and admin invited successfully.');
    }

    public function show(Organization $organization)
    {
        $adminUser = User::where('organization_id', $organization->id)
            ->whereHas('roles', fn($q) => $q->where('name', 'organization-admin'))
            ->first();

        return view('superadmin.organizations.show', compact('organization', 'adminUser'));
    }

    public function edit(Organization $organization)
    {
        return view('superadmin.organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('organizations', 'name')->ignore($organization->id)],
            'org_prefix' => ['nullable', 'string', 'max:10'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'background_image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('organizations', 'public');
        }

        if ($request->hasFile('background_image')) {
            $validated['background_image'] = $request->file('background_image')->store('organizations', 'public');
        }

        $organization->update($validated);

        return back()->with('status', 'Organization updated successfully.');
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();
        return back()->with('status', 'Organization deleted.');
    }

    public function resendInvite(Organization $organization)
    {
        $adminUser = User::where('organization_id', $organization->id)
            ->whereHas('roles', fn($q) => $q->where('name', 'organization-admin'))
            ->firstOrFail();

        $tempPassword = 'Admin@' . random_int(1000, 9999);
        $adminUser->update(['password' => Hash::make($tempPassword)]);

        Mail::to($adminUser->email)->send(new AdminInviteMail($organization, $adminUser, $tempPassword));

        return back()->with('status', 'Invite re-sent successfully to ' . $adminUser->email);
    }
}
