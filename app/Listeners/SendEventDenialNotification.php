<?php

namespace App\Listeners;

use App\Events\UserDeniedFromEvent;
use App\Mail\EventDeniedNotification;
use Illuminate\Support\Facades\Mail;

class SendEventDenialNotification
{
    public function handle(UserDeniedFromEvent $event)
    {
        // Send the denial email notification
        Mail::to($event->user->email)->send(new EventDeniedNotification($event->user, $event->event));
    }
}
