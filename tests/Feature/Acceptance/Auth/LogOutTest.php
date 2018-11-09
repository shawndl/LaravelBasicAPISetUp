<?php

namespace Tests\Feature\Acceptance\Auth;

use App\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogOutTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @group acceptance
     * @group Auth
     * @test
     */
    public function it_must_be_able_to_log_the_user_out()
    {
        $user = create(User::class);

        $this->signIn($user)
            ->withHeaders([
                'Authorization' => 'Bearer ' . JWTAuth::fromUser($user)
            ])
            ->json('post', route('logout'))
            ->assertStatus(200)
            ->assertJson(['message' => 'You are logged out!']);
        $this->assertFalse(Auth::check());
    }
}
