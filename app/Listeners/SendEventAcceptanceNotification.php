<?php

namespace App\Listeners;

use App\Events\UserAcceptedToEvent;
use App\Mail\EventJoinedNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEventAcceptanceNotification
{
    /**
     * Handle the event.
     */
    public function handle(UserAcceptedToEvent $event)
    {
        // Send the email
        Mail::to($event->user->email)->send(new EventJoinedNotification($event->user, $event->event));
    }
}

