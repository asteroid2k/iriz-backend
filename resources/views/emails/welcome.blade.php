@component('mail::message')
# Welcome
Hi {{$user->first_name}} ,
We're glad to have you at {{ config('app.name') }}, your Account has been created successfully,
Click the button to verify your Email.

@component('mail::button', ['url' => "{$url}/verify/{$user->email}",'color'=>'success'])
Verify my mail
@endcomponent

Danke,<br>
{{ config('app.name') }}
@endcomponent
