<?php

namespace App\Observers\Auth;

use App\Models\Auth\ConfirmationToken;

class ConfirmationTokenObserver
{
    /**
     * Handle the confirmation token "created" event.
     *
     * @param  \App\ConfirmationToken  $confirmationToken
     * @return void
     */
    public function creating(ConfirmationToken $confirmationToken)
    {
        optional($confirmationToken->user->confirmationToken)->delete();
    }

    /**
     * Handle the confirmation token "updated" event.
     *
     * @param  \App\ConfirmationToken  $confirmationToken
     * @return void
     */
    public function updated(ConfirmationToken $confirmationToken)
    {
        //
    }

    /**
     * Handle the confirmation token "deleted" event.
     *
     * @param  \App\ConfirmationToken  $confirmationToken
     * @return void
     */
    public function deleted(ConfirmationToken $confirmationToken)
    {
        //
    }

    /**
     * Handle the confirmation token "restored" event.
     *
     * @param  \App\ConfirmationToken  $confirmationToken
     * @return void
     */
    public function restored(ConfirmationToken $confirmationToken)
    {
        //
    }

    /**
     * Handle the confirmation token "force deleted" event.
     *
     * @param  \App\ConfirmationToken  $confirmationToken
     * @return void
     */
    public function forceDeleted(ConfirmationToken $confirmationToken)
    {
        //
    }
}
