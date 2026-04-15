<?php

namespace App\Notifications;

use App\Events\EventCreated;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;

class EventCreatedNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public function __construct(public Event $event) {}

    public function via(object $notifiable): array
    {
        return ['broadcast'];
    }

    public function toBroadcast(object $notifiable): EventCreated
    {
        return new EventCreated($this->event, $notifiable->id);
    }
}
