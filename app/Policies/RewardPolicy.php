<?php

namespace App\Policies;

use App\Models\Reward;
use App\Models\User;

class RewardPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('reward.browse');
    }

    public function redeem(User $user, Reward $reward): bool
    {
        return $user->can('reward.redeem')
            && $user->isStudent()
            && $reward->isAvailable()
            && $reward->isAccessibleBy($user);
    }
}
