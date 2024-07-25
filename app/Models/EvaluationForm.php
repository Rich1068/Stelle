<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        // Add other fillable attributes here if needed
    ];
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function questions()
    {
        return $this->hasMany(Question::class, 'form_id');
    }
}
