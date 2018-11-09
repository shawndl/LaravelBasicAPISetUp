<?php

namespace Tests\Feature\acceptance\user\profile;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePasswordAuthorisationTest extends TestCase
{
    /**
     * @group user
     * @group profile
     * @group acceptance
     * @test
     */
    public function a_user_must_be_signed_in_to_change_their_password()
    {
        $this->json('post', route('profile.password'), [
            'current_password' => 'secret',
            'password' =>'newpassword',
            'password_confirmation' => 'newpassword'
        ])->assertStatus(401);
    }
}
