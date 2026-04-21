<?php

namespace App\Http\Controllers;

use App\Actions\Like\LikeEventAction;
use App\Actions\Like\UnlikeEventAction;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;

class LikeController extends Controller
{
    public function __construct(
        private LikeEventAction $likeEvent,
        private UnlikeEventAction $unlikeEvent
    ) {}

    public function store(Event $event): RedirectResponse
    {
        $this->likeEvent->execute($event, auth()->user());

        return back()->with('success', 'Event liked.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $this->unlikeEvent->execute($event, auth()->user());

        return back()->with('success', 'Like removed.');
    }
}
