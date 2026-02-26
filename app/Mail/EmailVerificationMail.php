<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $newEmail;
    public $verificationCode;

    /**
     * Create a new message instance.
     */
    public function __construct($newEmail, $verificationCode)
    {
        $this->newEmail = $newEmail;
        $this->verificationCode = $verificationCode;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Code de vérification pour changement d\'email')
                    ->view('emails.email-verification')
                    ->with([
                        'newEmail' => $this->newEmail,
                        'verificationCode' => $this->verificationCode,
                    ]);
    }
}