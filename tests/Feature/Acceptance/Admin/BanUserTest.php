<?php

namespace Tests\Feature\Acceptance\Admin;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BanUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group acceptance
     * @group admin
     * @test
     */
    public function an_administrator_can_ban_a_user_from_the_site()
    {
        $user = create(User::class);
        $this->assertFalse($user->banned);
        $this->signInAdmin()
            ->json('post', route('admin.user.banned'), [
                'user_id' => $user->id,
                'access' => true
            ])->assertStatus(200)
            ->assertJson([
                'success' =>  "$user->name is now banned from the site"
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'banned' => 1
        ]);
    }
}
