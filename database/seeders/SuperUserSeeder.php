<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'superadmin@member.org.in'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Super@1234'),
                'organization_id' => null,
                'approved' => 1,
                'rejected' => 0,
                'email_verified_at' => now(),
            ]
        );

        $user->assignRole('super-admin');
    }
}
