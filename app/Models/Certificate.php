<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'cert_name',
        'cert_path',
        'design',
        'status_id'
    ];


    public function template()
    {
        return $this->belongsTo(CertTemplate::class);
    }
}

