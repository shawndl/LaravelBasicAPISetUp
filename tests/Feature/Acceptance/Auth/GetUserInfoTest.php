<?php

namespace Tests\Feature\Acceptance\Auth;

use App\Permission;
use App\Role;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetUserInfoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @group acceptance
     * @group Auth
     * @test
     */
    public function it_must_be_able_to_return_the_logged_users_information()
    {
        $user = create(User::class);

        $this->signIn($user)->json('get', route('me'))->assertJson([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => false
            ]
        ]);
    }


    /**
     * @group acceptance
     * @group Auth
     * @test
     */
    public function the_me_route_must_be_able_to_return_if_the_user_has_admin_access()
    {
        $user = $this->createAdmin();

        $this->signIn($user)->json('get', route('me'))->assertJson([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => true
            ]
        ]);
    }

    /**
     * @group acceptance
     * @group Auth
     * @test
     */
    public function a_user_must_be_signed_in()
    {
        $this->json('get', route('me'))->assertStatus(401);
    }
}
