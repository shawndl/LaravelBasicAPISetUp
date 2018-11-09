<?php

namespace App\Http\Controllers\Auth;

use App\Events\Auth\UserSignedUp;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Traits\Controllers\JsonResponseTrait;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    use JsonResponseTrait;

    /**
     * registers a user
     *
     * @param RegisterUserRequest $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterUserRequest $request, User $user)
    {
        try {
            $user = $user->create([
                'name' => $request->name,
                'email' => $request->email,
                'is_active' => false,
                'password' => bcrypt($request->password),
            ]);

            event(new UserSignedUp($user));
        } catch (\Exception $exception) {
            return $this->processingError($exception);
        }


        return $this->successResponse('Your registration is successful.  Please check your email to activate your account.', 201);
    }
}
