<?php

namespace App\Mail;

use App\Actions\Event\GenerateStudentQrAction;
use App\Models\EventUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentEventQrMail extends Mailable
{
    use Queueable, SerializesModels;

    public EventUser $registration;

    public function __construct(EventUser $registration)
    {
        $this->registration = $registration;
    }

    public function build(): self
    {
        $qrSvg = app(GenerateStudentQrAction::class)->execute($this->registration);

        return $this->subject('Your QR Code for '.$this->registration->event->title)
            ->view('emails.student-event-qr', [
                'registration' => $this->registration,
                'qrSvg' => $qrSvg,
            ]);
    }
}
