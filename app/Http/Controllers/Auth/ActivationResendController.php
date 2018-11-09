<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\ActivationResendRequest;
use App\Traits\Controllers\JsonResponseTrait;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivationResendController extends Controller
{
    use JsonResponseTrait;
    /**
     * resends the activation email
     *
     * @param ActivationResendRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function store(ActivationResendRequest $request, User $user)
    {
        $user = $user->where('email', $request->email)->first();

        if(!optional($user)->hasActivated())
        {
            event(new UserRequestActivationEmail($user));
        }

        return $this->messageResponse('An activation email has been sent');
    }
}
