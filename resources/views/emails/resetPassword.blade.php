@component('mail::message')
# Reset your Password

HI {{$name}} ,<br>
You requested to reset your password. Use the Verification Code below to reset your Password.
@component('mail::panel')
{{$code}}
@endcomponent
Do not share this Code
Danke,<br>
{{ config('app.name') }}
@endcomponent
