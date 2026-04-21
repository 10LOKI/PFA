<?php

namespace App\Http\Controllers;

use App\Mail\StudentEventQrMail;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

        // Generate unique QR token for this registration
        $token = Str::uuid()->toString();

        // Attach with qr_token
        $event->participants()->attach($user->id, [
            'status' => 'registered',
            'qr_token' => $token,
        ]);

        // Retrieve the registration pivot to get token
        $registration = $event->participants()
            ->where('user_id', $user->id)
            ->first();

        // Send QR code email
        Mail::to($user->email)->send(new StudentEventQrMail($registration->pivot));

        return back()->with('success', 'Registration confirmed. Check your email for QR code.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        abort_if(! auth()->user()->can('event.register'), 403);

        $event->participants()->detach(auth()->id());

        return back()->with('success', 'Registration cancelled.');
    }
}
