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
        'status_id',
        'org_role_id'
    ];
    public function member()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
    public function status()
    {
        return $this->belongsTo(ParticipantStatus::class, 'status_id');
    }

}
