<?php

namespace App\Http\Controllers;

use App\Models\EventUser;
use App\Notifications\EventCheckInNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use LogicException;

class StudentCheckInController extends Controller
{
    public function __invoke(Request $request, string $token): RedirectResponse|JsonResponse
    {
        $user = auth()->user();
        if (! $user->can('checkin.validate') && ! $user->isAdmin()) {
            abort(403);
        }

        $registration = EventUser::where('qr_token', $token)->firstOrFail();
        $event = $registration->event;

        $this->authorize('checkIn', $event);

        if ($event->status !== 'approved') {
            throw new LogicException('Event is not approved.');
        }

        if ($event->ends_at->isPast()) {
            throw new LogicException('Event has already ended.');
        }

        if ($registration->hasCheckedIn()) {
            $message = 'Student already checked in.';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'student_name' => $registration->user->name,
                ], 422);
            }

            return back()->with('error', $message);
        }

        $registration->update([
            'status' => 'checked_in',
            'checked_in_at' => now(),
        ]);

        $registration->user->notify(new EventCheckInNotification($event, $registration->user));

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Check-in successful',
                'student_name' => $registration->user->name,
                'time' => now()->format('H:i:s'),
                'points_earned' => $event->effectivePoints(),
                'pivot_id' => $registration->id,
            ]);
        }

        return back()->with('success', "Check-in successful for {$registration->user->name}.");
    }
}
