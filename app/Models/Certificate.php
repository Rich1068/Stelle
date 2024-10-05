<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'created_by',
        'cert_path',
        'design',
        'status_id'
    ];


    public function template()
    {
        return $this->belongsTo(CertTemplate::class);
    }
}

