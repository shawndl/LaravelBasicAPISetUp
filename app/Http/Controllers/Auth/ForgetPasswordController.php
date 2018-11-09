<?php

namespace App\Http\Controllers\Auth;

use App\Events\Auth\UserRequestForgotPassword;
use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ForgetPasswordController extends Controller
{
    /**
     * @var User
     */
    protected $user;

    /**
     * ForgetPasswordController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * checks if the users email exists if so it sends a reset password
     *
     * @param ForgetPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(ForgetPasswordRequest $request)
    {
        $user = $this->user->email($request->email);

        if(isset($user->id))
        {
            event(new UserRequestForgotPassword($user));
        }

        return response()->json([
           'success' =>  "A reset password has been sent to your email account {$request->email}!"
        ], 201);
    }
}
