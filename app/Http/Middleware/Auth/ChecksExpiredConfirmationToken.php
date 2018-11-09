<?php

namespace App\Http\Middleware\Auth;

use App\Models\Auth\ConfirmationToken;
use App\Traits\Controllers\JsonResponseTrait;
use Closure;

class ChecksExpiredConfirmationToken
{
    use JsonResponseTrait;

    /**
     * @var ConfirmationToken
     */
    protected $confirmationToken;

    /**
     * ChecksExpiredConfirmationToken constructor.
     * @param ConfirmationToken $confirmationToken
     */
    public function __construct(ConfirmationToken $confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
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
        $token = $request->route()->parameters()['confirmation_token'];
        $model = $this->confirmationToken->where('token', $token)->first();

        if(!isset($model) && !($model instanceof ConfirmationToken))
        {
            return $this->hasJsonError('No token was provided', 422);
        }

        if($model->hasExpired())
        {
            return $this->hasJsonError('Your confirmation token is expired, please request another one!', 422);
        }

        if(!isset($model->user->id))
        {
            return $this->hasJsonError('There was an issue with your token, please request another one', 422);
        }
        return $next($request);
    }
}
