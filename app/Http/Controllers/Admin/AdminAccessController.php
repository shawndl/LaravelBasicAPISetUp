<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AdminAccessRequest;
use App\Traits\Controllers\JsonResponseTrait;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class AdminAccessController extends Controller
{
    use JsonResponseTrait;

    /**
     * changes the users admin status
     *
     * @param AdminAccessRequest $request
     * @param User $user
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AdminAccessRequest $request, User $user, Role $role)
    {
        try {
            $user = $user->find($request->user_id);
            $role = $role->where('name', 'admin')->first();
            $message = '';
            if($request->access)
            {
                $user->assignRole($role->name);
                $message = "$user->name is now an admin";
            } else {
                $user->removeRole($role->name);
                $message = "$user->name is no longer an admin";
            }
        } catch (\Exception $exception) {
            return $this->processingError($exception);
        }

        return $this->successResponse($message);
    }

    /**
     * bans or unbans users from the site
     *
     * @param AdminAccessRequest $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function banned(AdminAccessRequest $request, User $user)
    {
        try {
            $user = $user->find($request->user_id);
            $user->banned();
            if($user->banned)
            {
                $message = "$user->name is now banned from the site";
            } else {
                $message = "$user->name is no longer banned from the site";
            }
        } catch (\Exception $exception) {
            $this->processingError($exception);
        }

        return $this->successResponse($message);
    }
}
