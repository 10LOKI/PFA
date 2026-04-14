<?php

namespace App\Actions\User;

use App\Models\User;

class UpgradeGradeAction
{
    private const GRADE_HIERARCHY = [
        'novice' => 1,
        'pilier' => 2,
        'ambassadeur' => 3,
    ];

    private const THRESHOLDS = [
        'novice' => 0,
        'pilier' => 50,
        'ambassadeur' => 150,
    ];

    public function execute(User $student): bool
    {
        $currentLevel = self::GRADE_HIERARCHY[$student->grade] ?? 1;
        $nextGrade = $this->getNextGrade($student->grade);

        if (! $nextGrade) {
            return false;
        }

        if ($student->total_hours < self::THRESHOLDS[$nextGrade]) {
            return false;
        }

        $student->update(['grade' => $nextGrade]);

        return true;
    }

    private function getNextGrade(string $current): ?string
    {
        $grades = array_keys(self::GRADE_HIERARCHY);
        $currentIndex = array_search($current, $grades, true);

        if ($currentIndex === false || $currentIndex >= count($grades) - 1) {
            return null;
        }

        return $grades[$currentIndex + 1];
    }

    public static function getThreshold(string $grade): int
    {
        return self::THRESHOLDS[$grade] ?? 0;
    }

    public static function getNextThreshold(string $grade): ?int
    {
        $nextGrade = (new self)->getNextGrade($grade);

        return $nextGrade ? self::THRESHOLDS[$nextGrade] : null;
    }
}
