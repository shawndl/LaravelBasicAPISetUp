<?php

namespace Tests\Feature\integration\models\auth;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConfirmationTokenTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User
     */
    protected $user;

    protected function setUp()
    {
        parent::setUp();
        $this->user = create(User::class);
        $this->user->generateConfirmationToken();
    }

    /**
     * @group integration
     * @group model
     * @group auth
     * @test
     */
    public function a_token_must_be_able_to_tell_if_it_has_expired()
    {
        $this->assertFalse($this->user->confirmationToken->hasExpired());
    }

    /**
     * @group integration
     * @group model
     * @group auth
     * @test
     */
    public function if_the_user_generates_a_second_token_then_the_first_one_will_be_deleted()
    {
        $this->user->generateConfirmationToken();
        $this->user->generateConfirmationToken();
        $this->user->generateConfirmationToken();
        $this->assertEquals(1, $this->user->confirmationToken()->count());
    }
}
