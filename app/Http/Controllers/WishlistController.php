<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;

class WishlistController extends Controller
{
    public function store(Event $event): RedirectResponse
    {
        $user = auth()->user();

        abort_if(! $user->can('event.register'), 403);

        $existing = $event->participants()
            ->wherePivot('user_id', $user->id)
            ->wherePivot('status', 'wishlist')
            ->exists();

        if ($existing) {
            return back()->with('info', 'Event already in wishlist.');
        }

        $event->participants()->attach($user->id, ['status' => 'wishlist']);

        return back()->with('success', 'Event added to wishlist.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        abort_if(! auth()->user()->can('event.register'), 403);

        $event->participants()
            ->wherePivot('user_id', auth()->id())
            ->wherePivot('status', 'wishlist')
            ->detach();

        return back()->with('success', 'Event removed from wishlist.');
    }
}
