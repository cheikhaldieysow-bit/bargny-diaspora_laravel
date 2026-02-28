<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PhoneVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $newPhone;
    public $verificationCode;

    /**
     * Create a new message instance.
     */
    public function __construct($newPhone, $verificationCode)
    {
        $this->newPhone = $newPhone;
        $this->verificationCode = $verificationCode;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Code de vérification pour changement de numéro de téléphone')
                    ->view('emails.phone-verification')
                    ->with([
                        'newPhone' => $this->newPhone,
                        'verificationCode' => $this->verificationCode,
                    ]);
    }
}