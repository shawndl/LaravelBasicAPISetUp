<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * signs in the User
     *
     * @param User|null $user
     * @return $this
     */
    protected function signIn(User $user = null)
    {
        $user = $user ?: create(User::class);
        $this->actingAs($user);
        return $this;
    }

    /**
     * signs in the User
     *
     * @param User|null $user
     * @return $this
     */
    protected function signInAdmin(User $user = null)
    {
        if(!is_null($user))
        {
            $user = ($user->hasRole('admin')) ? $user : $this->createAdmin();
        } else {
            $user = $this->createAdmin();
        }
        $this->actingAs($user);
        return $this;
    }

    /**
     * creates an admin user
     *
     * @return User
     */
    protected function createAdmin()
    {
        $user = create(User::class);
        $role = Role::create(['name' => 'admin']);
        $user->assignRole($role->name);
        return $user;
    }
}
