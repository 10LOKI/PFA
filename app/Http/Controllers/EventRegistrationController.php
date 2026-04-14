<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;

class EventRegistrationController extends Controller
{
    public function store(Event $event): RedirectResponse
    {
        $user = auth()->user();

        abort_if(! $user->can('event.register'), 403);
        abort_if(! $event->isApproved(), 403, 'Event is not available.');
        abort_if($event->isFull(), 422, 'Event is full.');

        $alreadyRegistered = $event->participants()
            ->wherePivot('user_id', $user->id)
            ->exists();

        abort_if($alreadyRegistered, 422, 'Already registered.');

        $event->participants()->attach($user->id, ['status' => 'registered']);

        return back()->with('success', 'Registration confirmed.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        abort_if(! auth()->user()->can('event.register'), 403);

        $event->participants()->detach(auth()->id());

        return back()->with('success', 'Registration cancelled.');
    }
}
