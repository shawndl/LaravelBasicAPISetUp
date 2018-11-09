<?php

namespace App\Mail\Auth;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    public $token;

    /**
     * @var User
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @param string $token
     * @param User $user
     */
    public function __construct(string $token, User $user)
    {
        $this->token = $token;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Activate Account')->markdown('emails.auth.activation');
    }
}
