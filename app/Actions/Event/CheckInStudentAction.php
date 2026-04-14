<?php

namespace App\Actions\Event;

use App\Actions\Points\CreditPointsAction;
use App\Actions\User\UpgradeGradeAction;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use LogicException;

class CheckInStudentAction
{
    public function __construct(
        private CreditPointsAction $creditPoints,
        private UpgradeGradeAction $upgradeGrade,
    ) {}

    public function execute(Event $event, User $student, string $qrToken): void
    {
        if ($event->qr_code_token !== $qrToken) {
            throw new LogicException('Invalid QR token.');
        }

        DB::transaction(function () use ($event, $student) {
            $pivot = $event->participants()
                ->wherePivot('user_id', $student->id)
                ->firstOrFail()
                ->pivot;

            if ($pivot->hasCheckedIn()) {
                throw new LogicException('Student already checked in.');
            }

            $event->participants()->updateExistingPivot($student->id, [
                'status' => 'checked_in',
                'checked_in_at' => now(),
                'points_earned' => $event->effectivePoints(),
            ]);

            $this->creditPoints->execute(
                user: $student,
                amount: $event->effectivePoints(),
                type: 'earned',
                description: "Check-in: {$event->title}",
                source: $event,
            );

            $student->increment('total_hours', $event->duration_hours);

            $this->upgradeGrade->execute($student);
        });
    }
}
