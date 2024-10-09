<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventReminderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $event;

    public function __construct($user, $event)
    {
        $this->user = $user;
        $this->event = $event;
    }

    public function build()
    {
        return $this->subject('Reminder: Event is starting soon!')
            ->view('emails.event_reminder')
            ->with([
                'user' => $this->user,
                'event' => $this->event,
            ]);
    }
}
