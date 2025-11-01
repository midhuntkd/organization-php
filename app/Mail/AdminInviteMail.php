<?php

namespace App\Mail;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Organization $organization,
        public User $adminUser,
        public string $tempPassword
    ) {}

    public function build()
    {
        return $this->from('alerts@member.org.in', $this->organization->name . ' Team')
            ->subject('Welcome to ' . $this->organization->name)
            ->markdown('mail.admin_invite', [
                'organization' => $this->organization,
                'user' => $this->adminUser,
                'password' => $this->tempPassword,
            ]);
    }
}
