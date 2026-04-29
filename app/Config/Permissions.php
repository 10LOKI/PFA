<?php

namespace App\Config;

/**
 * Permissions Configuration
 *
 * Central source of truth for role-based permissions.
 * Used by RegisterUserAction, UserSeeder, and authorization systems.
 */
class Permissions
{
    public const STUDENT = [
        'event.browse',
        'event.register',
        'event.checkin',
        'reward.browse',
        'reward.redeem',
        'comment.create',
        'feedback.create',
        'certificate.download',
    ];

    public const PARTNER = [
        'event.browse',
        'event.create',
        'event.update',
        'event.delete',
        // 'event.generate-qr',
        'checkin.validate',
        'student.rate',
        'reward.browse',
        'reward.update',
        'reward.delete',
    ];

    public const ADMIN = [
        'partner.kyc-approve',
        'partner.kyc-reject',
        'event.approve',
        'event.reject',
        'user.manage',
        'analytics.view',
        'reward.browse',
        'reward.update',
        'reward.delete',
    ];

    /**
     * Get all permissions for a role.
     */
    public static function forRole(string $role): array
    {
        return match ($role) {
            'student' => self::STUDENT,
            'partner' => self::PARTNER,
            'admin' => self::ADMIN,
            default => [],
        };
    }
}
