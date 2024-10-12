<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\UserDeleted;
use App\Mail\AccountDeleted;
use Illuminate\Support\Facades\Mail;

class SendAccountDeletedNotification
{

    /**
     * Handle the event.
     */
    public function handle(UserDeleted $event)
    {
        Mail::to($event->user->email)->send(new AccountDeleted($event->user));
    }
}
