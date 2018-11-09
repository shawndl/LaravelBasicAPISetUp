<?php

namespace Tests\Feature\integration\models\auth;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserConfirmationTokenTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @var User
     */
    protected $user;

    protected function setUp()
    {
        parent::setUp();
        $this->user = create(User::class, ['is_active' => false]);
        $this->user->generateConfirmationToken();
    }

    /**
     * @group integration
     * @group model
     * @group auth
     * @test
     */
    public function a_user_must_be_able_to_generate_a_confirmation_token()
    {

            $this->assertDatabaseHas('confirmation_tokens', [
                'user_id' => $this->user->id
            ]);
    }

    /**
     * @group integration
     * @group model
     * @group auth
     * @test
     */
    public function a_user_has_a_confirmation_token()
    {
        $this->assertEquals(1, $this->user->confirmationToken()->count());
    }

    /**
     * @group integration
     * @group model
     * @group auth
     * @test
     */
    public function a_user_can_confirm_if_they_are_active()
    {
        $this->assertFalse($this->user->hasActivated());
    }

    /**
     * @group integration
     * @group model
     * @group auth
     * @test
     */
    public function a_user_can_confirm_if_they_are_not_active()
    {
        $this->assertTrue($this->user->hasNotActivated());
    }
}
