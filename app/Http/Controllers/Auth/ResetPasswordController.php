<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\Auth\ConfirmationToken;
use App\Traits\Controllers\JsonResponseTrait;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Tymon\JWTAuth\Facades\JWTAuth;

class ResetPasswordController extends Controller
{
    use JsonResponseTrait;

    /**
     * @var ConfirmationToken
     */
    protected $token;

    /**
     * ResetPasswordController constructor.
     * @param ConfirmationToken $token
     */
    public function __construct(ConfirmationToken $token)
    {
        $this->token = $token;
    }

    public function reset(ResetPasswordRequest $request, string $token)
    {
        try {

            $tokenModel = $this->token->token($token);
            $user = $tokenModel->user;

            $user->update([
                'password' => bcrypt($request->password)
            ]);

            $token = JWTAuth::fromUser($user);
        } catch (\Exception $exception) {
            $this->processingError($exception);
        }

        return response()->json([
            'success' => 'Your password has been updated!',
            'meta' => [
                'token' => $token
            ]
        ], 201);
    }
}
