<?php

namespace Tests\Feature\acceptance\user\profile;

use App\Mail\UserAccount\PasswordUpdated;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;

use Swift_Message;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePasswordTest extends TestCase
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
        $this->user = create(User::class, ['password' => bcrypt('secret')]);
        $this->signIn($this->user);
        $this->setUpEmails();
        $this->response = $this
            ->signIn($this->user)
            ->json('post', route('profile.password'),[
            'current_password' => 'secret',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
    }

    /**
     * @group account
     * @group acceptance
     * @test
     */
    public function if_the_user_enters_the_correct_values_then_their_password_will_be_updated()
    {
        $this->response->assertJson(['success' => 'Your password has been updated!']);
    }

    /**
     * @group account
     * @group acceptance
     * @test
     */
    public function it_must_send_an_email_to_the_user_informing_them_their_password_was_updated()
    {
        $body = 'has received a request to reset the password for your account. If you did not request to reset your password, please ignore this email.';

        $this->seeEmailWasSent()
            ->seeEmailTo($this->user->email)
            ->seeEmailFrom('hello@example.com')
            ->seeEmailSubject('Password Updated')
            ->seeEmailContains($body);


    }
}
