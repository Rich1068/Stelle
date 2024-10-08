<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    public $token;

    /**
     * Create a new notification instance.
     *
     * @param string $token
     */
    
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        
        return (new MailMessage)
            ->subject('Please Verify Your Email Address')
            ->greeting('Hello!')
            ->line('Click the button below to verify your email address.')
            ->action('Verify Email', $verificationUrl)
            ->line('If you did not create an account, no further action is required.')
            ->subject('Please Verify Your Email Address')
            ->markdown('emails.verify-email', ['url' => $verificationUrl]);
    }
}

