@component('mail::message')
# Hi {{ $user->name }},

Your membership request for **{{ $organization->name }}** has been **rejected**.

**Reason:** {{ $title }}

@isset($description)
**Details:**
{{ $description }}
@endisset

If you believe this is a mistake, please reply to this email or You can resubmit request .

@component('mail::button', ['url' => $resubmitUrl])
Resubmit Request
@endcomponent

Thanks,<br>
{{ $organization->name }}
@endcomponent