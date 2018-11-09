<?php

namespace App\Http\Controllers\Auth;

use App\Http\Resources\Auth\UserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MeController extends Controller
{
    /**
     * returns the currently login User
     *
     * @param Request $request
     * @return UserResource
     */
    public function me(Request $request)
    {
        if(is_null($request->user()))
        {
            return response()->json([], 200);
        }

        return new UserResource($request->user());
    }
}
