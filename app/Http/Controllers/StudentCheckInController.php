<?php

namespace App\Http\Controllers;

use App\Models\EventUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use LogicException;

class StudentCheckInController extends Controller
{
    public function __invoke(Request $request, string $token): RedirectResponse
    {
        $user = auth()->user();
        if (! $user->can('checkin.validate') && ! $user->isAdmin()) {
            abort(403);
        }

        $registration = EventUser::where('qr_token', $token)->firstOrFail();
        $event = $registration->event;

        if ($event->status !== 'approved') {
            throw new LogicException('Event is not approved.');
        }

        if ($event->ends_at->isPast()) {
            throw new LogicException('Event has already ended.');
        }

        if ($registration->hasCheckedIn()) {
            throw new LogicException('Student already checked in.');
        }

        $registration->update([
            'status' => 'checked_in',
            'checked_in_at' => now(),
        ]);

        return back()->with('success', "Check-in successful for {$registration->user->name}.");
    }
}
