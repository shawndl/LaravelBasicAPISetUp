<?php

namespace App\Providers;

use App\Models\Auth\ConfirmationToken;
use App\Observers\Auth\ConfirmationTokenObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ConfirmationToken::observe(ConfirmationTokenObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
