<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    private const PERMISSIONS = [
        'student' => [
            'event.browse', 'event.register', 'event.checkin',
            'reward.browse', 'reward.redeem',
            'comment.create', 'feedback.create', 'certificate.download',
        ],
        'partner' => [
            'event.browse', 'event.create', 'event.update', 'event.delete',
            'event.generate-qr', 'checkin.validate', 'student.rate',
        ],
    ];

    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'role'     => $data['role'],
            ]);

            $user->givePermissionTo(self::PERMISSIONS[$data['role']]);

            return $user;
        });
    }
}
