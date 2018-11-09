<?php

namespace App;

use App\Models\Auth\ConfirmationToken;
use App\Models\Auth\UserSocial;
use App\Traits\Models\HasConfirmationTrait;
use App\Traits\Models\HasNameTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasRoles, HasConfirmationTrait, HasNameTrait;

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_active', 'banned'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function getRouteKey()
    {
        return 'name';
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * finds User by username
     *
     * @param Builder $builder
     * @param string $value
     * @return Builder
     */
    public function scopeName(Builder $builder, string $value)
    {
        return $builder->where('name', strtolower($value))->first();
    }

    /**
     * finds User by email
     *
     * @param Builder $builder
     * @param string $value
     * @return Builder
     */
    public function scopeEmail(Builder $builder, string $value)
    {
        return $builder->where('email', $value)->first();
    }

    /**
     * finds User by email or username
     *
     * @param Builder $builder
     * @param string $value
     * @return Builder
     */
    public function scopeNameEmail(Builder $builder, string $value)
    {
        return $builder->where('email', $value)
            ->orWhere('name', strtolower($value))
            ->first();
    }

    /**
     * a User can have an activation token
     */
    public function token()
    {
        return $this->hasMany(ConfirmationToken::class);
    }

    /**
     * a user can have many social logins
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function social()
    {
        return $this->hasMany(UserSocial::class);
    }

    /**
     * does the user have this soical account
     *
     * @param string $service
     * @return boolean
     */
    public function hasSocialLink(string $service)
    {
        return (bool)$this->social->where('service', $service)->count();
    }

    /**
     * toggles between of the user is banned from the site
     *
     * @return void
     */
    public function banned()
    {
        $this->banned = !$this->banned;
        $this->save();
    }

    /**
     *  gets name for url searches
     * @return string
     */
    public function url()
    {
        return str_replace(' ', '-', strtolower($this->name));
    }
}
