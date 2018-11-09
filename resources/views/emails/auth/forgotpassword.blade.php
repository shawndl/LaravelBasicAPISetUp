@component('mail::message')
# Reset Password Assistance

Hi {{ $user->name }}

We received a request to reset the password associated with this e-mail address. If you made this request, please follow the instructions below.

Click the link below to reset your password:

@component('mail::button', ['url' => config('app.endpoint') . '/auth/reset?token=' . $token])
    Reset Password
@endcomponent

If you did not request to have your password reset you can safely ignore this email. Rest assured your customer account is safe.

Thanks,<br>

{{ config('app.name') }}
@endcomponent