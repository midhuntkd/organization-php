<?php

namespace App\Mail;

use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterOTPMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Organization $organization, public int $otp) {}

    public function build()
    {
        return $this->subject('Your Registration OTP')
            ->markdown('mail.register_otp', [
            'otp'         => $this->otp,
            'organization' => $this->organization,
        ]);
    }
}
