<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'cert_id',
        'user_id'
    ];
}
