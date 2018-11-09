<?php

namespace Tests\Feature\Acceptance\Auth;

use App\User;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForgetPasswordTest extends TestCase
{
    use RefreshDatabase, \TestEmailsTrait;

    /**
     * @var array
     */
    protected $post;

    /**
     * @var TestResponse
     */
    protected $response;

    /**
     * @var User
     */
    protected $user;

    protected function setUp()
    {
        parent::setUp();
        $this->setUpEmails();
        $this->user = create(User::class);
        $this->post['email'] = $this->user->email;
    }

    /**
     * @group acceptance
     * @group Auth
     * @test
     */
    public function a_reset_password_call_must_be_able_to_create_a_token()
    {
        $this->assertCount(0, $this->user->token);
        $this->sendResponse();
        $this->assertCount(1, $this->user->fresh()->token);
    }

    /**
     * @group acceptance
     * @group Auth
     * @test
     */
    public function a_reset_password_call_must_send_an_email_with_a_token()
    {
        $this->sendResponse();
        $this->seeEmailWasSent()
            ->seeEmailTo($this->user->email)
            ->seeEmailFrom('hello@example.com')
            ->seeEmailSubject('ForageMap.com Password Assistance')
            ->seeEmailContains("Hi {$this->user->name}")
            ->seeEmailContains('We received a request to reset the password associated with this e-mail address. If you made this request, please follow the instructions below.')
            ->seeEmailContains('Click the link below to reset your password:')
            ->seeEmailContains('Reset Password');
    }

    /**
     * @group acceptance
     * @group Auth
     * @test
     */
    public function if_the_email_address_is_not_valid_no_email_must_be_sent()
    {
        $this->post['email'] = 'fake@gmail.com';
        $this->seeEmailWasNotSent();
    }

    /**
     * @group acceptance
     * @group Auth
     * @test
     */
    public function a_reset_password_call_must_return_a_message_to_the_user()
    {
        $this->sendResponse();

        $this->response
            ->assertStatus(201)
            ->assertJson([
               'success' => 'A reset password has been sent to your email account ' . $this->user->email . '!'
            ]);
    }

    /**
     * sets the response object
     */
    private function sendResponse()
    {
        $url = route('forget');
        $this->response = $this->json('post', $url, $this->post);
    }
}
