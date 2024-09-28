<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'cert_id',
        'user_id',
        'cert_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function certificate()
    {
        return $this->belongsTo(Certificate::class, 'cert_id');
    }

}
