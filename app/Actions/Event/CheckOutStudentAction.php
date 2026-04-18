<?php

namespace App\Actions\Event;

use App\Actions\Points\CreditPointsAction;
use App\Actions\User\UpgradeGradeAction;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use LogicException;

class CheckOutStudentAction
{
    public function __construct(
        private CreditPointsAction $creditPoints,
        private UpgradeGradeAction $upgradeGrade,
    ) {}

    public function execute(Event $event, User $student): void
    {
        DB::transaction(function () use ($event, $student) {
            $pivot = $event->participants()
                ->wherePivot('user_id', $student->id)
                ->firstOrFail()
                ->pivot;

            if (! $pivot->hasCheckedIn()) {
                throw new LogicException('Student has not checked in.');
            }

            if ($pivot->hasCheckedOut()) {
                throw new LogicException('Student already checked out.');
            }

            $checkedOutAt = now();
            $event->participants()->updateExistingPivot($student->id, [
                'status' => 'checked_in',
                'checked_out_at' => $checkedOutAt,
            ]);

            $pivot->refresh();

            $proRatedHours = $pivot->proRatedHours();
            $pointsEarned = (int) round($proRatedHours * $event->effectivePoints());

            if ($pointsEarned > 0) {
                $event->participants()->updateExistingPivot($student->id, [
                    'points_earned' => $pointsEarned,
                ]);

                $this->creditPoints->execute(
                    user: $student,
                    amount: $pointsEarned,
                    type: 'earned',
                    description: "Check-out: {$proRatedHours}h / {$event->duration_hours}h at {$event->title}",
                    source: $event,
                );

                $student->increment('total_hours', $proRatedHours);

                $this->upgradeGrade->execute($student);
            }
        });
    }
}
