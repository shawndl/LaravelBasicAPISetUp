<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Resources\Auth\UserResource;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function login(LoginUserRequest $request)
    {
        $email = $this->getEmail($request);
        if (!$token = auth()->attempt(['email' => $email, 'password' => $request->password])) {
            return response()->json([
                'errors' => [
                    'email' => ['Sorry we couldn\'t sign you in with those details.']
                ]
            ], 422);
        }

        return (new UserResource($request->user()))
            ->additional([
                'meta' => [
                    'token' => $token
                ]
            ]);
    }

    /**
     * if the user provided an email address then it returns it
     * or it finds the user by name and returns the email address of the user
     *
     * @param Request $request
     * @return mixed
     */
    private function getEmail(Request $request)
    {
        if(filter_var($request->email, FILTER_VALIDATE_EMAIL))
        {
            return $request->email;
        }
        $user = User::where('name', strtolower($request->email))->first();
        if(!isset($user->email))
        {
            return '';
        }
        return $user->email;
    }
}
