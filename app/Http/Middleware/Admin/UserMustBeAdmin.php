<?php

namespace App\Http\Middleware\Admin;

use Closure;

class UserMustBeAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(is_null($request->user()) || !$request->user()->hasRole('admin'))
        {
            return response()->json([
                'error' => 'unauthorised'
            ], 403);
        }
        return $next($request);
    }
}
