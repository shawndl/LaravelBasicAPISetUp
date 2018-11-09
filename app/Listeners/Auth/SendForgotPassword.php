<?php

namespace App\Listeners\Auth;

use App\Events\Auth\UserRequestForgotPassword;
use App\Mail\Auth\ForgotPasswordEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendForgotPassword
{
    /**
     * Handle the event.
     *
     * @param  UserRequestForgotPassword  $event
     * @return void
     */
    public function handle(UserRequestForgotPassword $event)
    {
        Mail::to($event->user)
            ->queue(new ForgotPasswordEmail($event->user->generateConfirmationToken(),  $event->user));
    }
}
