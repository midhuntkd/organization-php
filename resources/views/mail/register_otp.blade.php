@component('mail::message')
<p>Hello,</p>
<p>Your OTP for {{ $organization->name }} registration is:</p>
<h2>{{ $otp }}</h2>
<p>This code expires in 10 minutes.</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent