<?php

namespace App\Actions\Like;

use App\Models\Event;
use App\Models\Like;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use LogicException;

class UnlikeEventAction
{
    public function execute(Event $event, User $user): bool
    {
        $like = Like::where('user_id', $user->id)
            ->where('likeable_id', $event->id)
            ->where('likeable_type', Event::class)
            ->first();

        if (! $like) {
            throw new LogicException('Like not found.');
        }

        return DB::transaction(fn () => $like->delete());
    }
}
