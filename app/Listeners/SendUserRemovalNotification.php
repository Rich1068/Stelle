<?php

namespace App\Listeners;

use App\Events\UserRemovedFromEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\UserRemovedNotification;
use Illuminate\Support\Facades\Mail;

class SendUserRemovalNotification
{
    public function handle(UserRemovedFromEvent $event)
    {
        // Send email to the removed user
        Mail::to($event->user->email)->send(new UserRemovedNotification($event->user, $event->eventDetails));
    }
}
