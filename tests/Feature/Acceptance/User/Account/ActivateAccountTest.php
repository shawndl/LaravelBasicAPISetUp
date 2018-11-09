<?php

namespace Tests\Feature\acceptance\user\account;

use App\Models\Auth\ConfirmationToken;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivateAccountTest extends TestCase
{
    use RefreshDatabase, \TestEmailsTrait;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var TestResponse
     */
    protected $response;

    protected function setUp()
    {
        parent::setUp();

        $user = make(User::class, ['name' => 'joesmith', 'is_active' => false])->toArray();
        $user['password'] = 'secret';
        $user['password_confirmation'] = 'secret';
        $this->json('post', route('register'), $user);
        $this->user = User::where('name', 'joesmith')->first();
        $this->setUpEmails();
        $token = ConfirmationToken::where('user_id', $this->user->id)->first();
        $this->response = $this->json('get', route('activate', ['confirmation_token' => $token->token]));


    }

    /**
     * @group user
     * @group account
     * @group acceptance
     * @test
     */
    public function a_user_must_be_able_to_activate_their_account()
    {
        $this->assertTrue($this->user->fresh()->hasActivated());
    }

    /**
     * @group user
     * @group account
     * @group acceptance
     * @test
     */
    public function a_confirmation_message_must_be_sent_back_to_the_browser()
    {
        $this->response->assertJson([
            'success' => 'Congratulations, your account has been activated!'
        ]);
    }
}
