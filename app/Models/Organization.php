<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'icon',
        'contact_email',
        'contact_phone',
        'owner_id',
        'is_open'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Relationship: Users who are members of this organization.
     */
    public function members()
    {
        return $this->hasMany(OrganizationMember::class, 'organization_id');
    }

    /**
     * Relationship: Events created for this organization.
     */
    public function events()
    {
        return $this->hasMany(Event::class); // One organization has many events
    }
}
