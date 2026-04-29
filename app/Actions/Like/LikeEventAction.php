<?php

namespace App\Actions\Like;

use App\Models\Event;
use App\Models\Like;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LikeEventAction
{
    public function execute(Event $event, User $user): Like
    {
        $existing = Like::where('user_id', $user->id)
            ->where('likeable_id', $event->id)
            ->where('likeable_type', Event::class)
            ->first();

        if ($existing) {
            throw new \LogicException('Event already liked.');
        }

        return DB::transaction(function () use ($event, $user) {
            return Like::create([
                'user_id' => $user->id,
                'likeable_id' => $event->id,
                'likeable_type' => Event::class,
            ]);
        });
    }
}
