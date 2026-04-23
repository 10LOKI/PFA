<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventCheckOutNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Event $event;

    public User $student;

    public int $pointsEarned;

    public function __construct(Event $event, User $student, int $pointsEarned)
    {
        $this->event = $event;
        $this->student = $student;
        $this->pointsEarned = $pointsEarned;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Check-out Complete - '.$this->event->title)
            ->greeting('Hello '.$this->student->name.'!')
            ->line('Mission completed! You have successfully checked out.')
            ->line('**Mission:** '.$this->event->title)
            ->line('**Points Earned:** '.$this->pointsEarned.' PTS')
            ->line('**Total Hours:** '.$this->student->fresh()->total_hours.' HRS')
            ->line('Thank you for your participation!')
            ->salutation('Regards, '.config('app.name'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'student_id' => $this->student->id,
            'points_earned' => $this->pointsEarned,
            'type' => 'event_checkout',
        ];
    }
}
