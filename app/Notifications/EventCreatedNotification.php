<?php

namespace App\Notifications;

use App\Events\EventCreated;
use App\Models\Event;
use App\Models\Notification as NotificationModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;

class EventCreatedNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function via(object $notifiable): array
    {
        return ['broadcast'];
    }

    public function toBroadcast(object $notifiable): EventCreated
    {
        return new EventCreated($this->event, $notifiable->id);
    }

    /**
     * Save notification to custom table
     */
    public function toCustomDatabase(object $notifiable): void
    {
        NotificationModel::create([
            'user_id' => $notifiable->id,
            'type' => 'event_created',
            'title' => 'Nouvel événement',
            'message' => $this->event->title,
            'link' => route('events.show', $this->event),
            'event_id' => $this->event->id,
            'read' => false,
        ]);
    }
}
