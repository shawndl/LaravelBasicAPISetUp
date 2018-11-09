<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Admin\OAuthRequest;
use App\Http\Resources\Auth\UserResource;
use App\Traits\Controllers\JsonResponseTrait;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialLoginController extends Controller
{
    use JsonResponseTrait;
    /**
     * @var User
     */
    protected $user;

    /**
     * SocialLoginController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * @param OAuthRequest $request
     * @return JsonResponse
     */
    public function login(OAuthRequest $request)
    {
        try {
            $service = $request->service;
            $serviceUser = Socialite::driver($service)
                ->stateless()
                ->userFromToken($request->token);
            $user = $this->getExistingUser($service, $serviceUser);

            if(!isset($user->id))
            {
                $user = $this->user->create([
                    'name' => $serviceUser->getName(),
                    'email' => $serviceUser->getEmail(),
                    'is_active' => 1
                ]);
            }

            if($this->needsToCreateSocial($user, $service))
            {
                $user->social()->create([
                    'social_id' => $serviceUser->getId(),
                    'service' => $service
                ]);
            }

            $token = JWTAuth::fromUser($user);
        } catch (\Exception $exception) {
            $this->processingError($exception);
        }

        return (new UserResource($user))
            ->additional([
                'success' => 'Congratulations, your account has been activated!',
                'meta' => [
                    'token' => $token
                ]
            ]);
    }

    private function needsToCreateSocial(User $user, string $service)
    {
        return !$user->hasSocialLink($service);
    }

    /**
     * checks if the user exists either in the users table or social user table
     *
     * @param $service
     * @param $serviceUser
     * @return mixed
     */
    private function getExistingUser($service, $serviceUser)
    {
        return $this->user
            ->where('email', $serviceUser->getEmail())
            ->orWhereHas('social', function($query) use ($service, $serviceUser) {
                $query->where('social_id', $serviceUser->getId())->where('service', $service);
            })->first();
    }
}
