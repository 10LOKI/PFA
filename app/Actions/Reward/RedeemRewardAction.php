<?php

namespace App\Actions\Reward;

use App\Actions\Points\CreditPointsAction;
use App\Models\Reward;
use App\Models\RewardRedemption;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use LogicException;

class RedeemRewardAction
{
    public function __construct(private CreditPointsAction $creditPoints) {}

    public function execute(User $user, Reward $reward): RewardRedemption
    {
        return DB::transaction(function () use ($user, $reward) {
            // Guard: reward must be available
            if (! $reward->isAvailable()) {
                throw new LogicException('Reward is not available.');
            }

            // Guard: grade gate
            if (! $reward->isAccessibleBy($user)) {
                throw new LogicException('Your grade does not meet the requirement for this reward.');
            }

            // Guard: sufficient balance
            if ($user->points_balance < $reward->points_cost) {
                throw new LogicException('Insufficient points balance.');
            }

            // Burn points (negative amount = debit)
            $this->creditPoints->execute(
                user:        $user,
                amount:      -$reward->points_cost,
                type:        'burned',
                description: "Redemption: {$reward->title}",
                source:      $reward,
            );

            // Decrement stock if limited
            if ($reward->stock !== null) {
                $reward->decrement('stock');
            }

            // Create redemption record
            return RewardRedemption::create([
                'user_id'      => $user->id,
                'reward_id'    => $reward->id,
                'points_spent' => $reward->points_cost,
                'status'       => 'pending',
                'redeemed_at'  => now(),
            ]);
        });
    }
}
