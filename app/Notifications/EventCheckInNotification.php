<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventCheckInNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Event $event;

    public User $student;

    public function __construct(Event $event, User $student)
    {
        $this->event = $event;
        $this->student = $student;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Check-in Confirmed - '.$this->event->title)
            ->greeting('Hello '.$this->student->name.'!')
            ->line('Your check-in for the mission has been confirmed:')
            ->line('**Mission:** '.$this->event->title)
            ->line('**Location:** '.$this->event->city)
            ->line('**Checked in at:** '.now()->format('d/m/Y H:i'))
            ->line('Please complete check-out after the mission to earn your points.')
            ->salutation('Regards, '.config('app.name'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'student_id' => $this->student->id,
            'type' => 'event_checkin',
        ];
    }
}
