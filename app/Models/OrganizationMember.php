<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationMember extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'organization_id',
        'status_id'
    ];
}
