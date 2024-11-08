<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventParticipant;

class EventParticipantSeeder extends Seeder
{
    public function run()
    {
        // Generate 20 event participants with your specified requirements
        EventParticipant::factory()->count(40)->create();
    }
}
