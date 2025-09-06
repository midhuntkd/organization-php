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
            'name'              => 'Alappuzha Jilla Pravasi Samajam',
            'is_default'        => true,
            'org_prefix'        => 'AJPS',
            'slug'              => 'alappuzha-jilla-Pravasi-samajam',
            'logo'              => 'organizations/logos/default.png',  // Put a file in storage/app/public/organizations/logos
            'background_image'  => 'organizations/backgrounds/default-bg.jpg',
        ]);

        $sampleOrganization1 = Organization::firstOrCreate([
            'name'              => 'AJPSTest',
            'is_default'        => true,
            'org_prefix'        => 'AJPSTest',
            'slug'              => 'apjs',
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
            'email' => 'admin@member.org.in',
        ], [
            'name' => 'Super Admin',
            'organization_id' => $organization->id,
            'password' => Hash::make('Admin@1234'), // change after first login
            'email_verified_at' => now(),
            'approved' => true,
            'password_changed' => true,
        ]);

        // Assign role to user
        $admin->assignRole($superAdminRole);

        $organizationAdmin = User::firstOrCreate([
            'email' => 'alappuzhajillapravasisamajam@gmail.com',
        ], [
            'name' => 'ORG Admin',
            'organization_id' => $sampleOrganization->id,
            'password' => Hash::make('Admin@1234'), // change after first login
            'email_verified_at' => now(),
            'approved' => true,
            'password_changed' => true,
        ]);

        $organizationAdmin->assignRole($organizationAdminRole);

        $organizationAdmin1 = User::firstOrCreate([
            'email' => 'Admin@ajpstest.com',
        ], [
            'name' => 'ORG Admin',
            'organization_id' => $sampleOrganization1->id,
            'password' => Hash::make('Admin@AJPS1'), // change after first login
            'email_verified_at' => now(),
            'approved' => true,
            'password_changed' => true,
        ]);

        $organizationAdmin1->assignRole($organizationAdminRole);
    }
}
