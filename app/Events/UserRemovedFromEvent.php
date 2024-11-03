<?php

namespace App\Events;

use App\Models\User;
use App\Models\Event;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRemovedFromEvent
{
    use Dispatchable, SerializesModels;

    public $user;
    public $eventDetails;

    public function __construct(User $user, Event $eventDetails)
    {
        $this->user = $user;
        $this->eventDetails = $eventDetails;
    }
}