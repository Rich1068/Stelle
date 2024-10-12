<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventDeniedNotification extends Mailable implements ShouldQueue
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
        return $this->subject('Your Request to Join an Event Has Been Denied')
                    ->view('emails.event_denied') // Create a Blade file for this view
                    ->with([
                        'user' => $this->user,
                        'event' => $this->event,
                    ]);
    }
}
