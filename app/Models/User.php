<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;

class User extends Authenticatable implements CanResetPassword
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'google_id',
        'role_id',
        'gender',
        'country_id',
        'password',
        'description',
        'contact_number',
        'profile_picture',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function role()
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }

    public function userEvents()
    {
        return $this->hasMany(UserEvent::class);
    }
    public function certificates()
    {
        return $this->hasManyThrough(
            Certificate::class,
            CertUser::class,
            'user_id', // Foreign key on CertUser table
            'id', // Foreign key on Certificate table
            'id', // Local key on User table
            'cert_id' // Local key on CertUser table
        );
    }
}
