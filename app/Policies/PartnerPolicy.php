<?php

namespace App\Policies;

use App\Models\Partner;
use App\Models\User;

class PartnerPolicy
{
    public function approve(User $user, Partner $partner): bool
    {
        return $user->can('partner.kyc-approve') && $user->isAdmin();
    }

    public function reject(User $user, Partner $partner): bool
    {
        return $user->can('partner.kyc-reject') && $user->isAdmin();
    }

    public function update(User $user, Partner $partner): bool
    {
        return $user->id === $partner->user_id;
    }
}
