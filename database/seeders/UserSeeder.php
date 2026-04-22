<?php

namespace Database\Seeders;

use App\Models\Establishment;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private const PERMISSIONS = [
        'student' => [
            'event.browse', 'event.register', 'event.checkin',
            'reward.browse', 'reward.redeem',
            'comment.create', 'feedback.create', 'certificate.download',
        ],
        'partner' => [
            'event.browse', 'event.create', 'event.update', 'event.delete',
            // 'event.generate-qr', // deprecated
            'checkin.validate', 'student.rate',
        ],
        'admin' => [
            'partner.kyc-approve', 'partner.kyc-reject',
            'event.approve', 'event.reject',
            'user.manage', 'analytics.view',
        ],
    ];

    public function run(): void
    {
        $establishments = Establishment::all();

        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@admin.com',
                'password' => 'password',
                'role' => 'admin',
                'kyc_verified' => true,
                'is_certified_partner' => true,
            ],
            [
                'name' => 'Partner User',
                'email' => 'partner@partner.com',
                'password' => 'password',
                'role' => 'partner',
                'kyc_verified' => true,
                'is_certified_partner' => true,
            ],
            [
                'name' => 'Student User',
                'email' => 'student@student.com',
                'password' => 'password',
                'role' => 'student',
                'establishment_id' => $establishments->random()->id,
                'points_balance' => 100,
                'total_hours' => 10,
            ],
            [
                'name' => 'Admin',
                'email' => 'role@role.com',
                'password' => 'password',
                'role' => 'admin',
                'kyc_verified' => true,
                'is_certified_partner' => true,
            ],
            [
                'name' => 'Partner One',
                'email' => 'partner@role.com',
                'password' => 'password',
                'role' => 'partner',
                'kyc_verified' => true,
                'is_certified_partner' => true,
                'city' => 'Paris',
            ],
            [
                'name' => 'Student One',
                'email' => 'student@role.com',
                'password' => 'password',
                'role' => 'student',
                'establishment_id' => $establishments->random()->id,
                'city' => 'Paris',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            $user->syncPermissions(self::PERMISSIONS[$role]);

            if ($role === 'student' && $establishments->isNotEmpty()) {
                $user->grades()->firstOrCreate([
                    'establishment_id' => $userData['establishment_id'] ?? $establishments->random()->id,
                ], [
                    'level' => 'Licence 2',
                    'field' => 'Informatique',
                    'academic_year' => 2025,
                ]);
            }
        }
    }
}
