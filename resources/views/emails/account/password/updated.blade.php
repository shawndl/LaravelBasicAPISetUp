@component('mail::message')
# Password Updated

{{ config('app.url') }} has received a request to reset the password for your account. If you did not request to reset your password, please ignore this email.


Thanks,<br>
{{ config('app.name') }}
@endcomponent
