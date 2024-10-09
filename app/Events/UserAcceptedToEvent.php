<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Event;

class UserAcceptedToEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $event;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Event $event)
    {
        $this->user = $user;
        $this->event = $event;
    }
}

