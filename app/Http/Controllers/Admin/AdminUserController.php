<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Auth\UserResource;
use App\Traits\Controllers\JsonResponseTrait;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdminUserController extends Controller
{
    use JsonResponseTrait;

    /**
     * gets index by pagination
     *
     * @param User $users
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(User $users)
    {

        try {
            $users = $users->paginate(20);
        } catch (\Exception $exception) {
            return $this->processingError($exception);
        }

        return UserResource::collection($users);
    }

    /**
     * returns information about a single user
     *
     * @param User $users
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function show(User $model, string $user)
    {
        try {
            $model = $model->where('name', $user)->firstOrFail();
        } catch (\Exception $exception) {
            if($exception instanceof ModelNotFoundException)
            {
                return $this->hasJsonError('User not found!', 404);
            }
            return $this->processingError($exception);
        }

        return new UserResource($model);
    }
}
