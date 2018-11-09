<?php

namespace Tests\Feature\Acceptance\Auth;

use App\User;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var array
     */
    protected $post;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $user;

    /**
     * @group acceptance
     * @group auth
     * @test
     */
    public function a_user_must_be_active_to_login()
    {
        $this->sendResponse(false)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => [
                        'Your account is not active, please check your email'
                    ]
                ]
            ]);
        $this->assertFalse(Auth::check());
    }

    /**
     * @group acceptance
     * @group auth
     * @test
     */
    public function it_must_return_a_token_if_email_and_password_matches()
    {
        $this->sendResponse()->assertJsonStructure([
            'data' => ['id', 'name', 'email'], 'meta' => ['token']
        ]);
    }

    /**
     * @group acceptance
     * @group auth
     * @test
     */
    public function if_the_credentials_do_not_match_an_error_will_be_returned()
    {
        $this->sendResponse(true, 'notsecret')
            ->assertJson([
            'errors' => ['email' => ['Sorry we couldn\'t sign you in with those details.']
            ]
        ]);
    }

    /**
     * @group acceptance
     * @group auth
     * @test
     */
    public function a_user_must_be_able_to_login_with_a_username()
    {
        $this->sendResponse(true, 'secret', true)
            ->assertStatus(200)
            ->assertJsonStructure([
            'data' => ['id', 'name', 'email'], 'meta' => ['token']
        ]);
    }

    /**
     * @group acceptance
     * @group auth
     * @test
     */
    public function it_must_not_allow_a_user_to_log_in_if_they_are_banned()
    {
        $this->sendResponse(true, 'secret', true, true)
            ->assertStatus(403)
            ->assertJson([
                'error' => 'Your account has been banned from the site'
            ]);
    }

    /**
     * sends login response
     * @param bool $active
     * @param string $password
     * @param bool $username
     * @param bool $banned
     * @return TestResponse
     */
    private function sendResponse($active = true, $password = 'secret', $username = false, $banned = false)
    {
        $this->user = create(User::class, [
            'is_active' => $active,
            'password' => bcrypt('secret'),
            'banned' => $banned
        ])->toArray();

        $this->post['email'] = ($username) ? $this->user['name'] : $this->user['email'];
        $this->post['password'] = $password;
        $this->url = route('login');
        return $this->json('post', $this->url, $this->post);
    }
}