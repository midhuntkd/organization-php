@component('mail::message')
# Hi {{ $user->name }},

Your membership for **{{ $organization->name }}** has been **approved**.

@isset($verifyUrl)
> **Before you can log in**, please verify your email:
@component('mail::button', ['url' => $verifyUrl])
Verify Email
@endcomponent
@endisset

@component('mail::panel')
**Login URL:** {{ $loginUrl }}
**Email:** {{ $user->email }}
**Temporary Password:** {{ $plainPassword }}
@endcomponent

> For your security, you’ll be asked to **set a new password** after your first login.

If you didn’t request this account, please ignore this email.

Thanks,
{{ config('app.name') }}
@endcomponent