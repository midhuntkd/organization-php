@component('mail::message')
# Hi {{ $user->name }},

Your membership request for **{{ $organization->name }}** has been **rejected**.

**Reason:** {{ $title }}

@isset($description)
**Details:**
{{ $description }}
@endisset

If you believe this is a mistake, please reply to this email or contact support.

Thanks,<br>
{{ config('app.name') }}
@endcomponent