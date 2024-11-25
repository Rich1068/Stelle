<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;
    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }
    public function evaluationForm()
    {
        return $this->hasOne(EventEvaluationForm::class);
    }

    public function userEvent()
    {
        return $this->hasOne(UserEvent::class);
    }
    public function eventCertTemplates()
    {
        return $this->hasMany(CertTemplate::class);
    }

    protected $fillable = [
        'title', 'description', 'start_date', 'end_date', 'address', 
        'start_time', 'end_time', 'capacity', 'event_banner', 'mode', 'organization_id'
    ];
    use HasFactory;

}
