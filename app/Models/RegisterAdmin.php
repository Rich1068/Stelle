<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterAdmin extends Model
{
    protected $fillable = [
        'user_id',
        'status_id',
    ];
    use HasFactory;
}
