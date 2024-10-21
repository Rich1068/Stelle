<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    public function users()
    {
        return $this->hasMany(User::class, 'province_id'); // 'region_id' is the foreign key in the 'users' table
    }
}
