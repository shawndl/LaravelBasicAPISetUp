<?php

namespace Tests\Feature\Acceptance\Auth;

use App\Models\Auth\ConfirmationToken;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase, \TestEmailsTrait;

    /**
     * @var array
     */
    protected $post;

    /**
     * @var User
     */
    protected $user;

    protected function setUp()
    {
        parent::setUp();
        $this->setUpEmails();
        $this->user = create(User::class);
        $this->post['password'] = 'secret';
        $this->post['password_confirmation'] = 'secret';
    }

    /**
     * @group acceptance
     * @group Auth
     * @test
     */
    public function a_reset_password_request_must_have_a_new_password()
    {
        $this->post['password'] = '';
        $this->sendResponse()
            ->assertStatus(422)
            ->assertJsonFragment([
                'password' => ['The password field is required.']
            ]);
    }


    /**
     * @group acceptance
     * @group Auth
     * @test
     */
    public function a_reset_password_request_must_have_a_confirm_password_matches()
    {
        $this->post['password'] = 'secretpassword';
        $this->sendResponse()
            ->assertStatus(422)
            ->assertJsonFragment([
               'password' => ['The password confirmation does not match.']
            ]);
    }

    /**
     * @group acceptance
     * @group Auth
     * @test
     */
    public function a_reset_password_request_must_have_a_valid_token()
    {
        $this->sendResponse('faketoken')
            ->assertStatus(422)
            ->assertJson([
               'error' => 'No token was provided'
            ]);
    }

    /**
     * @group acceptance
     * @group Auth
     * @test
     */
    public function a_reset_password_request_must_have_a_token_that_is_not_expired()
    {
        $token = $this->user->generateConfirmationToken();
        $tokenModel = ConfirmationToken::where('token', $token);
        $tokenModel->update([
           'expires_at' =>  Carbon::createFromDate(2000, 1, 1)
        ]);
        $this->sendResponse($token)
            ->assertStatus(422)
            ->assertJson([
                'error' => 'Your confirmation token is expired, please request another one!'
            ]);
    }

    /**
     * @group acceptance
     * @group Auth
     * @test
     */
    public function a_reset_password_request_must_update_the_password()
    {
        $this->sendResponse()
            ->assertStatus(201)
            ->assertJsonFragment([
               'success' => 'Your password has been updated!'
            ])->assertJsonStructure([
               'success', 'meta' => ['token']
            ]);

        $loginAttempt = auth()
            ->attempt(['email' => $this->user->email, 'password' => 'secret']);

        $this->assertTrue(is_string($loginAttempt));
    }

    /**
     * sets the response object
     *
     * @param null $token
     * @return TestResponse
     */
    private function sendResponse($token = null)
    {
        if(is_null($token))
        {
            $token = $this->user->generateConfirmationToken();
        }
        $url = route('reset', ['token' => $token]);
        return $this->json('post', $url, $this->post);
    }
}
