<?php

namespace App\Mail;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Organization $organization, public User $user, public string $message = '', public string $customSubject = '') {}

    public function build()
    {
        if (empty($this->customSubject)) {
            $this->subject = 'Registration Successful ';
        }
        return $this->from('alerts@member.org.in', $this->organization->name)
        ->subject($this->customSubject)
            ->markdown('mail.register_success', [
                'organization' => $this->organization,
                'user' => $this->user,
                'message' => $this->message,
            ]);
    }
}


