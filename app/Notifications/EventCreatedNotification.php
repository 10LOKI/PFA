<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('events.show', $this->event);

        return (new MailMessage)
            ->subject('New Event Created - '.$this->event->title)
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('A new event has been created:')
            ->line('**Event:** '.$this->event->title)
            ->line('**Category:** '.($this->event->category ?? 'General'))
            ->line('**Location:** '.$this->event->city.', '.$this->event->address)
            ->line('**Date:** '.$this->event->starts_at->format('d/m/Y H:i'))
            ->line('**Points Reward:** '.$this->event->effectivePoints().' PTS')
            ->action('View Event', $url)
            ->line('If you are the event partner, please wait for admin approval.')
            ->salutation('Regards, '.config('app.name'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'type' => 'event_created',
        ];
    }
}
