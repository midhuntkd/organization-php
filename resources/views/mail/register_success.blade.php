@component('mail::message')
# Hi {{ $user->name }},

@if (!empty($message))
{{ $message  }}
@else
Your registration successfully completed for **{{ $organization->name }}** . You will be notified once admin approved.
@endif

For your security, you’ll be asked to **set a new password** after your first login.

If you didn’t request this account, please ignore this email.

Thanks,
{{ $organization->name }}
@endcomponent