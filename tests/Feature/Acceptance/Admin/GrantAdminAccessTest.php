<?php

namespace Tests\Feature\Acceptance\Admin;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GrantAdminAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group acceptance
     * @group admin
     * @test
     */
    public function an_administrator_can_grant_admin_access_to_a_user()
    {
        $user = create(User::class);
        $this->assertFalse($user->hasRole('admin'));

        $this->signInAdmin()
            ->json('post', route('admin.user.access'), [
                'user_id' => $user->id,
                'access' => true
            ])->assertStatus(200)
            ->assertJson([
               'success' =>  "$user->name is now an admin"
            ]);

        $this->assertTrue($user->refresh()->hasRole('admin'));
    }
}
