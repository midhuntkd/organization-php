<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or get the default organization
        $organization = Organization::firstOrCreate([
            'name' => 'Admin Organization',
            'admin_organization' => true,
            'org_prefix' => 'ADN',
            'slug'              => 'admin-organization',
            'logo'              => 'organizations/logos/default.png',  // Put a file in storage/app/public/organizations/logos
            'background_image'  => 'organizations/backgrounds/default-bg.jpg',
        ]);

        $sampleOrganization = Organization::firstOrCreate([
            'name'              => 'Sample Organization',
            'is_default'        => true,
            'org_prefix'        => 'SMP',
            'slug'              => 'Sample-organization',
            'logo'              => 'organizations/logos/default.png',  // Put a file in storage/app/public/organizations/logos
            'background_image'  => 'organizations/backgrounds/default-bg.jpg',
        ]);

        // Create the super-admin role
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $memberRole = Role::firstOrCreate(['name' => 'member']);
        $organizationAdminRole = Role::firstOrCreate(['name' => 'organization-admin']);

        // Optional: define some default permissions
        $permissions = [
            'create organization',
            'view users',
            'approve users',
            'assign roles',
            'delete users',
        ];

        // Create permissions and assign to role
        foreach ($permissions as $perm) {
            $permission = Permission::firstOrCreate(['name' => $perm]);
            $superAdminRole->givePermissionTo($permission);
        }

        // Create admin user
        $admin = User::firstOrCreate([
            'email' => 'admin@mainOrganization.com',
        ], [
            'name' => 'Super Admin',
            'organization_id' => $organization->id,
            'password' => Hash::make('admin@147258'), // change after first login
            'email_verified_at' => now(),
            'approved' => true,
            'password_changed' => true,
        ]);

        // Assign role to user
        $admin->assignRole($superAdminRole);

        $organizationAdmin = User::firstOrCreate([
            'email' => 'admin@sampleOrganization.com',
        ], [
            'name' => 'ORG Admin',
            'organization_id' => $sampleOrganization->id,
            'password' => Hash::make('admin@147258'), // change after first login
            'email_verified_at' => now(),
            'approved' => true,
            'password_changed' => true,
        ]);

        $organizationAdmin->assignRole($organizationAdminRole);
    }
}
