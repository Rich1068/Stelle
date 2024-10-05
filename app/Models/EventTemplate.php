<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'template_id'
    ];
}
