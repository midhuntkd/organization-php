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

class MemberRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Organization $organization,
        public string $title,
        public ?string $description = null
    ) {}

    public function build()
    {
        return $this->subject('Application Rejected - ' . $this->organization->name)
            ->markdown('mail.member_rejected', [
                'user'         => $this->user,
                'organization' => $this->organization,
                'title'        => $this->title,
                'description'  => $this->description,
            ]);
    }
}