<?php

namespace App\Models\Auth;

use App\Traits\Models\HasUserTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ConfirmationToken extends Model
{
    use HasUserTrait;

    protected $dates = [
        'expires_at'
    ];

    protected $fillable = [
        'token', 'expires_at'
    ];

    public $timestamps = false;

    /**
     * routes will find this record by token
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'token';
    }

    public function scopeToken(Builder $builder, string $token)
    {
        return $builder->where('token', $token)->first();
    }

    /**
     * has the token expired
     *
     * @return bool
     */
    public function hasExpired()
    {
        return $this->freshTimestamp()->gt($this->expires_at);
    }
}
