<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Mail\EventReminderNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendEventReminders extends Command
{
    protected $signature = 'send:event-reminders';
    protected $description = 'Send email reminders to users before their event starts';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $reminderBeforeEvent = Carbon::now()->addHours(24); // Send reminder 24 hours before event
        
        $now = Carbon::now();

        // Fetch events happening within the next 24 hours
        $eventsStartingSoon = Event::whereRaw("CONCAT(date, ' ', start_time) BETWEEN ? AND ?", [
            $now->toDateTimeString(),
            $reminderBeforeEvent->toDateTimeString()
        ])->get();

        foreach ($eventsStartingSoon as $event) {
            // Get all participants who have not received a reminder
            $participants = EventParticipant::where('event_id', $event->id)
                ->where('status_id', 1) // Accepted participants
                ->where('reminder_sent', false) // Only participants who haven't been reminded
                ->get();

            foreach ($participants as $participant) {
                $user = $participant->user; // Assuming there's a user relationship
                // Send the reminder email
                Mail::to($user->email)->send(new EventReminderNotification($user, $event));

                // Mark reminder as sent for this participant
                $updated = $participant->update(['reminder_sent' => true]);
                \Log::info("Reminder update result for participant {$participant->id}: " . ($updated ? 'Success' : 'Failed'));
            }
        }

        $this->info('Event reminder emails sent successfully.');
    }
}
