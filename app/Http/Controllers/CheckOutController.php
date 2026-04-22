<?php

namespace App\Http\Controllers;

use App\Actions\Event\CheckOutStudentAction;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use LogicException;

class CheckOutController extends Controller
{
    public function __construct(private CheckOutStudentAction $checkOut) {}

    public function store(Request $request, Event $event): RedirectResponse|JsonResponse
    {
        $request->validate([
            'student_id' => ['required', 'exists:users,id'],
        ]);

        $student = User::findOrFail($request->student_id);
        $authUser = auth()->user();

        // Students can only check themselves out; partners/admins can check any student
        if (! $authUser->isAdmin() && ! $authUser->isPartner()) {
            abort_if($student->id !== $authUser->id, 403, 'You can only check out yourself.');
        }

        try {
            $this->checkOut->execute($event, $student);

            $pivot = $event->participants()->where('user_id', $student->id)->first()->pivot;
            $pointsEarned = $pivot ? ($pivot->points_earned ?? 0) : 0;

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Check-out confirmed for {$student->name}",
                    'points_earned' => $pointsEarned,
                    'total_hours' => $student->total_hours,
                ]);
            }

            return back()->with('success', "Check-out confirmed for {$student->name}. {$pointsEarned} points earned.");
        } catch (LogicException $e) {
            $message = $e->getMessage();
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 422);
            }

            return back()->with('error', $message);
        }
    }
}
