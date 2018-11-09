<?php

namespace App\Http\Controllers\Auth;

use App\Http\Resources\Auth\UserResource;
use App\Models\Auth\ConfirmationToken;
use App\Traits\Controllers\JsonResponseTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;

class ActivationController extends Controller
{
    use JsonResponseTrait;

    /**
     * activate the User account if the token matches the User
     *
     * @param ConfirmationToken $token
     * @return mixed
     */
    public function activate(ConfirmationToken $token, string $userToken)
    {
        try {
            $token = $token->token($userToken);
            $user = $token->user;
            $user->is_active = true;
            $user->save();
            $token = JWTAuth::fromUser($user);
        } catch (\Exception $exception) {
            if($exception->getMessage() !== 'Creating default object from empty value')
            {
                $this->processingError($exception);
            }
        }

        return (new UserResource($user))
            ->additional([
                'success' => 'Congratulations, your account has been activated!',
                'meta' => [
                    'token' => $token
                ]
            ]);
    }
}
