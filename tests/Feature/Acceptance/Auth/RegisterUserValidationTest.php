<?php

namespace Tests\Feature\Acceptance\Auth;

use App\User;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterUserValidationTest extends TestCase
{
    use RefreshDatabase;

    protected $post;

    /**
     * @var string
     */
    protected $url;


    protected function setUp()
    {
        parent::setUp();
        $this->post = make(User::class)->toArray();
        $this->post['password'] = 'secret';
        $this->post['password_confirmation'] = 'secret';
        $this->url = route('register');

    }

    /**
     * @group account
     * @group acceptance
     * @test
     */
    public function a_registration_requires_a_name()
    {

        $this->post['name'] = '';
        $this->validate()
            ->assertStatus(422)
            ->assertJsonFragment([
            'The name field is required.'
        ]);
    }

    /**
     * @group account
     * @group acceptance
     * @test
     */
    public function a_registration_name_cannot_exceed_255_characters()
    {

        $this->post['name'] = str_repeat(".",300);
        $this->validate()
            ->assertStatus(422)
            ->assertJsonFragment([
                'The name may not be greater than 255 characters.'
            ]);
    }

    /**
     * @group account
     * @group acceptance
     * @test
     */
    public function a_registration_name_must_be_unique()
    {
        create(User::class, ['name' => 'sam']);
        $this->post['name'] = 'sam';
        $this->validate()
            ->assertStatus(422)
            ->assertJsonFragment([
                'The name has already been taken.'
            ]);
    }

    /**
     * @group account
     * @group acceptance
     * @test
     */
    public function a_registration_requires_a_email()
    {

        $this->post['email'] = '';
        $this->validate()
            ->assertStatus(422)
            ->assertJsonFragment([
                'The email field is required.'
            ]);
    }

    /**
     * @group account
     * @group acceptance
     * @test
     */
    public function a_registration_email_cannot_exceed_255_characters()
    {

        $this->post['email'] = str_repeat(".",300) . '@gmail.com';
        $this->validate()
            ->assertStatus(422)
            ->assertJsonFragment([
                'The email may not be greater than 255 characters.'
            ]);
    }

    /**
     * @group account
     * @group acceptance
     * @test
     */
    public function a_registration_email_must_be_unique()
    {
        create(User::class, ['email' => 'sam@gmail.com']);
        $this->post['email'] = 'sam@gmail.com';
        $this->validate()
            ->assertStatus(422)
            ->assertJsonFragment([
                'The email has already been taken.'
            ]);
    }

    /**
     * @group account
     * @group acceptance
     * @test
     */
    public function a_registration_email_must_be_an_email()
    {
        $this->post['email'] = 'sam';
        $this->validate()
            ->assertStatus(422)
            ->assertJsonFragment([
                'The email must be a valid email address.'
            ]);
    }

    /**
     * @group account
     * @group acceptance
     * @test
     */
    public function a_registration_requires_a_password()
    {

        $this->post['password'] = '';
        $this->validate()
            ->assertStatus(422)
            ->assertJsonFragment([
                'The password field is required.'
            ]);
    }

    /**
     * @group account
     * @group acceptance
     * @test
     */
    public function a_registration_password_fields_must_match()
    {

        $this->post['password'] = 'password';
        $this->validate()
            ->assertStatus(422)
            ->assertJsonFragment([
                'The password confirmation does not match.'
            ]);
    }

    /**
     * @return TestResponse
     */
    private function validate()
    {
        return $this->json('post', $this->url, $this->post);
    }
}
