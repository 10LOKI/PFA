<?php

namespace App\Events;

use App\Models\Event;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EventCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $data;

    public function __construct(public Event $event, public int $recipientId)
    {
        $this->data = [
            'event' => [
                'id' => $this->event->id,
                'title' => $this->event->title,
                'category' => $this->event->category,
                'points' => $this->event->points_reward,
                'date' => $this->event->starts_at->toIso8601String(),
                'city' => $this->event->city,
                'link' => route('events.show', $this->event),
            ],
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('notifications.'.$this->recipientId),
        ];
    }
}
