<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Student
            'event.browse',
            'event.register',
            'event.checkin',
            'reward.browse',
            'reward.redeem',
            'comment.create',
            'feedback.create',
            'certificate.download',

            // Partner
            'event.create',
            'event.update',
            'event.delete',
            'event.generate-qr',
            'checkin.validate',
            'student.rate',

            // Admin
            'partner.kyc-approve',
            'partner.kyc-reject',
            'event.approve',
            'event.reject',
            'user.manage',
            'analytics.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}
