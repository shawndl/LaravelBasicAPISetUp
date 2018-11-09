<?php

namespace App\Providers;

use App\Events\Auth\UserRequestActivationEmail;
use App\Events\Auth\UserRequestForgotPassword;
use App\Events\Auth\UserSignedUp;
use App\Listeners\Auth\SendActivationEmail;
use App\Listeners\Auth\SendForgotPassword;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserSignedUp::class => [
            SendActivationEmail::class
        ],
        UserRequestActivationEmail::class => [
            SendActivationEmail::class
        ],
        UserRequestForgotPassword::class => [
            SendForgotPassword::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
