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

    public function create(User $user): bool
    {
        return $user->isPartner();
    }

    public function update(User $user, Reward $reward): bool
    {
        return $user->can('reward.update') && $user->id === $reward->partner_id;
    }

    public function delete(User $user, Reward $reward): bool
    {
        return $user->can('reward.delete') && $user->id === $reward->partner_id;
    }
}
