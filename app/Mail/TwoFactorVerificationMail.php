<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwoFactorVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $name;

    public function __construct($code, $name = null)
    {
        $this->code = $code;
        $this->name = $name;
    }

    public function build()
    {
        return $this->subject('Your GlamBook Verification Code')
                    ->view('emails.two-factor-verification')
                    ->with([
                        'code' => $this->code,
                        'name' => $this->name ?? 'Valued Artist',
                    ]);
    }
}