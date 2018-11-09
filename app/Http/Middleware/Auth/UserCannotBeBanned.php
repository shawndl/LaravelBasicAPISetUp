<?php

namespace App\Http\Middleware\Auth;

use App\Traits\Controllers\JsonResponseTrait;
use App\User;
use Closure;

class UserCannotBeBanned
{
    use JsonResponseTrait;

    /**
     * @var User
     */
    protected $user;

    /**
     * RedirectIfUserIsNotActive constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(empty($request->email))
        {
            return $next($request);
        }
        $user = $this->user
            ->nameEmail($request->email);
        if(isset($user->id) && $user->banned)
        {
            return $this->hasJsonError('Your account has been banned from the site', 403);
        }

        return $next($request);
    }
}
