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
    public function usertable()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    use HasFactory;
}
