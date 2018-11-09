<?php

namespace App\Http\Controllers\Profile;

use App\Http\Requests\Account\ChangeUserPasswordRequest;
use App\Mail\UserAccount\PasswordUpdated;
use App\Traits\Controllers\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{
    use JsonResponseTrait;
    /**
     * @param ChangeUserPasswordRequest $request
     * @return JsonResponse
     */
    public function store(ChangeUserPasswordRequest $request)
    {
        try {
            Mail::to($request->user())->send(new PasswordUpdated());

            $request->user()->update([
                'password' => bcrypt($request->password)
            ]);
        } catch (\Exception $exception) {
            return $this->processingError($exception);
        }

        return response()->json([
            'success' => 'Your password has been updated!'
        ], 200);
    }
}
