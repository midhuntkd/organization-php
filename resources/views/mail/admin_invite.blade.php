@component('mail::message')
# Welcome, {{ $user->name }}

Your organization **{{ $organization->name }}** has been created successfully.

You have been invited as an **Organization Admin**.

@component('mail::panel')
**Login Email:** {{ $user->email }}  
**Temporary Password:** {{ $password }}
@endcomponent

@component('mail::button', ['url' => route('member_login', $organization->slug)])
Login Now
@endcomponent

> Please log in and change your password immediately.

Thanks,<br>
{{ config('app.name') }}
@endcomponent