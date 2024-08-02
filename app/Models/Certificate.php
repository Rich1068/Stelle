<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'cert_path',
        'design',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function template()
    {
        return $this->belongsTo(CertTemplate::class);
    }
}

