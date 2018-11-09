<?php

namespace Tests\Feature\acceptance\user\profile;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\View\Factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserProfilePageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group Account
     * @group acceptance
     * @test
     */
    public function a_user_must_be_logged_in_to_access_the_profile_page()
    {
        $this->json('post', route('profile.update'), [
            'name' => 'new',
            'email' => 'new@gmail.com'
        ])->assertStatus(401);
    }

    /**
     * @group Account
     * @group acceptance
     * @test
     */
    public function if_the_user_submits_an_edit_profile_request_then_the_users_details_will_be_changed()
    {
        $user = create(User::class, ['name' => 'happy']);

        $this->signIn($user)->json('post', route('profile.update'), [
            'name' => 'new',
            'email' => 'new@gmail.com'
        ])->assertStatus(200)->assertJson([
            'success' => 'Your Profile has been updated!'
        ]);
        $this->assertEquals('New', $user->fresh()->name);
    }
}
