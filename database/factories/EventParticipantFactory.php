<?php

namespace Database\Factories;

use App\Models\EventParticipant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventParticipantFactory extends Factory
{
    protected $model = EventParticipant::class;

    public function definition()
    {
        // Get a collection of user IDs between 3 and 50, shuffled to ensure randomness
        static $userIds = null;

        if ($userIds === null) {
            $userIds = User::whereBetween('id', [3, 50])->pluck('id')->shuffle();
        }

        // Use Sequence to assign a unique user_id for each EventParticipant
        return [
            'user_id' => $userIds->pop(), // Assign a unique user ID and remove it from the collection
            'event_id' => 1, // Fixed event_id as 1
            'status_id' => 1, // Fixed status_id as 1
        ];
    }
}
