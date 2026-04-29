<?php

namespace App\Actions\Points;

use App\Models\PointsTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreditPointsAction
{
    public function execute(
        User $user,
        int $amount,
        string $type,
        string $description,
        ?Model $source = null
    ): PointsTransaction {
        return DB::transaction(function () use ($user, $amount, $type, $description, $source) {
            $balanceAfter = $user->points_balance + $amount;

            $transaction = PointsTransaction::create([
                'user_id' => $user->id,
                'type' => $type,
                'amount' => $amount,
                'balance_after' => $balanceAfter,
                'source_type' => $source ? get_class($source) : null,
                'source_id' => $source?->id,
                'description' => $description,
            ]);

            User::where('id', $user->id)->increment('points_balance', $amount);

            return $transaction;
        });
    }
}
