<?php
/**
 * Created by PhpStorm.
 * User: shawnlegge
 * Date: 9/4/18
 * Time: 3:22 PM
 */

namespace App\Traits\Models;


use App\Models\Auth\ConfirmationToken;

trait HasConfirmationTrait
{
    /**
     * generates a confirmation token for the User
     */
    public function generateConfirmationToken()
    {
        return $this->confirmationToken()->create([
            'token' => str_random(200),
            'expires_at' => $this->getConfirmTokenExpire()
        ])->token;
    }

    /**
     * a User has one confirmation token
     *
     * @return mixed
     */
    public function confirmationToken()
    {
        return $this->hasOne(ConfirmationToken::class, 'user_id');
    }

    /**
     * has the User confirmed their email address
     *
     * @return boolean
     */
    public function hasActivated()
    {
        return ((int)$this->is_active === 1) ? true : false;
    }

    /**
     * has the User confirmed their email address
     *
     * @return boolean
     */
    public function hasNotActivated()
    {
        return ((int)$this->is_active === 1) ? false : true;
    }

    /**
     * generates an expirary date of 30 minutes from now
     *
     * @return string
     */
    protected function getConfirmTokenExpire()
    {
        return $this->freshTimestamp()->addMinutes(60 * 24);
    }
}