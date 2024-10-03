<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventEvaluationForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'form_id',
        'status_id'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
    public function evalForm()
    {
        return $this->belongsTo(EvaluationForm::class, 'form_id');
    }
    public function answer()
    {
        return $this->hasMany(Answer::class, 'event_form_id');
    }
}
