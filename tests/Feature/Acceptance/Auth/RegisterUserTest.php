<?php

namespace Tests\Feature\Acceptance\Auth;

use App\User;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterUserTest extends TestCase
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

    protected function setUp()
    {
        parent::setUp();
        $this->setUpEmails();
        $this->post = make(User::class, ['email' => 'testemail@test.com'])->toArray();
        $this->post['password'] = 'secret';
        $this->post['password_confirmation'] = 'secret';
        $url = route('register');
        $this->response = $this->json('post', $url, $this->post);
    }

    /**
     * @group acceptance
     * @group Auth
     * @test
     */
    public function a_successful_registration_must_must_add_a_user_to_the_database()
    {
        $this->assertEquals(1, User::count());
        $this->assertDatabaseHas('users', [
            'name' => strtolower($this->post['name'])
        ]);
    }

    /**
     * @group acceptance
     * @group Auth
     * @test
     */
    public function a_successful_registration_must_return_a_success_mess()
    {
        $this->response->assertStatus(201)
            ->assertJson([
                "success" => "Your registration is successful.  Please check your email to activate your account."
            ]);
    }

    /**
     * @group acceptance
     * @group Auth
     * @test
     */
    public function only_guests_can_register_new_account()
    {
        $this->signIn()
            ->json('post', route('register', $this->post))
            ->assertStatus(403);
    }

    /**
     * @group Auth
     * @group acceptance
     * @test
     */
    public function it_must_sign_the_user_in_after_registration()
    {
        $this->assertFalse(Auth::check());
    }

    public function a_registration_must_return_a_token_and_the_user()
    {
        $this->response->assertJsonStructure([
            'data' => [
                'id', 'created', 'name', 'email', 'banned', 'is_admin', 'number_locations'
            ],
            'meta' => ['token'],
            'success'
        ]);
    }

    /**
     * @group Auth
     * @group acceptance
     * @test
     */
    public function a_token_must_be_created_after_a_user_registers()
    {
        $user = User::first();
        $this->assertDatabaseHas('confirmation_tokens', [
            'user_id' => $user->id
        ]);
    }

    /**
     * @group Auth
     * @group acceptance
     * @test
     */
    public function a_user_confirmation_email_must_be_sent_after_registering()
    {
        $this->seeEmailWasSent()
            ->seeEmailTo('testemail@test.com')
            ->seeEmailFrom('hello@example.com')
            ->seeEmailSubject('Activate Account')
            ->seeEmailContains('Please follow the link below to activate your account. The link will remain valid for 30 minutes.')
            ->seeEmailContains('Your user account with the e-mail address testemail@test.com has been created.');
    }
}
