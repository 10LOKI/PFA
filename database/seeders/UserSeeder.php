<?php

namespace Database\Seeders;

use App\Config\Permissions;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@admin.com',
                'password' => 'password',
                'role' => 'admin',
                'kyc_verified' => true,
            ],
            [
                'name' => 'Partner User',
                'email' => 'partner@partner.com',
                'password' => 'password',
                'role' => 'partner',
                'kyc_verified' => true,
            ],
            [
                'name' => 'Student User',
                'email' => 'student@student.com',
                'password' => 'password',
                'role' => 'student',
                'points_balance' => 100,
                'total_hours' => 10,
            ],
            [
                'name' => 'Admin',
                'email' => 'role@role.com',
                'password' => 'password',
                'role' => 'admin',
                'kyc_verified' => true,
            ],
            [
                'name' => 'ayoub One',
                'email' => 'partner@role.com',
                'password' => 'password',
                'role' => 'partner',
                'kyc_verified' => true,
                'city' => 'Paris',
            ],
            [
                'name' => 'Student One',
                'email' => 'student@role.com',
                'password' => 'password',
                'role' => 'student',
                'city' => 'Paris',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            $user->syncPermissions(Permissions::forRole($role));
        }
    }
}
