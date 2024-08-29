<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'user_id', 'status_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function status()
    {
        return $this->belongsTo(ParticipantStatus::class, 'status_id');
    }

    public static function hasJoinedEvent(int $userId, int $eventId, int $statusId): bool
    {
        return self::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->where('status_id', $statusId)
            ->exists();
    }
}

