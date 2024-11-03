<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserRemovedNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $eventDetails;

    public function __construct(User $user, Event $eventDetails)
    {
        $this->user = $user;
        $this->eventDetails = $eventDetails;
    }

    public function build()
    {
        return $this->subject('You have been removed from an event')
                    ->view('emails.user_removed')
                    ->with([
                        'user' => $this->user,
                        'event' => $this->eventDetails,
                    ]);
    }
}
