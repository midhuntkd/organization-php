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

class MemberApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Organization $organization,
        public string $plainPassword,
        public string $loginUrl,
        public ?string $verifyUrl = null
    ) {}

    public function build()
    {
        $subject = 'Your account is approved - ' . $this->organization->name;

        return $this->from('alerts@member.org.in', $this->organization->name)
            ->subject($subject)
            ->markdown('mail.member_approved', [
                'user'         => $this->user,
                'organization' => $this->organization,
                'plainPassword' => $this->plainPassword,
                'loginUrl'     => $this->loginUrl,
                'verifyUrl'    => $this->verifyUrl,
            ]);
    }
}
