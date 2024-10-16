<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;  //for email verification
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\ResetPassword as CustomResetPasswordNotification;  //custom notification class
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $dates = ['deleted_at'];

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
        'birthdate',
        'email_verified_at'
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
     * The attributes that should be cast.
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

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        // Use the custom ResetPassword notification and pass the token
        $this->notify(new CustomResetPasswordNotification($token));
    }

    /**
     * Send the email verification notification.
     * If you want to customize the email verification notification,
     * you can override this method.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\CustomVerifyEmail);  // Use your custom email verification notification if needed
    }

    // Define relationships
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
    public function certUser()
    {
        return $this->hasMany(CertUser::class);
    }

    public function eventParticipant()
    {
        return $this->hasMany(EventParticipant::class);
    }
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
    public function registerAdminRequest()
    {
        return $this->hasOne(RegisterAdmin::class); 
    }
    public function eventsCreated()
    {
        return $this->hasMany(UserEvent::class, 'user_id');
    }
}
