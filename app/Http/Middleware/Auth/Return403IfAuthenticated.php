<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Support\Facades\Auth;

class Return403IfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return response()->json([
                'error' => 'unauthorised'
            ], 403);
        }
        return $next($request);
    }
}
