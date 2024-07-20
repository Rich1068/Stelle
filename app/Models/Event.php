<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    protected $fillable = [
        'title', 
        'description', 
        'date', 
        'address', 
        'start_time', 
        'end_time', 
        'capacity', 
        'event_banner',
        'mode'
    ];
    use HasFactory;

}
