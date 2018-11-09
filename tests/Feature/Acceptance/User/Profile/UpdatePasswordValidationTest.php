<?php

namespace Tests\Feature\acceptance\user\profile;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePasswordValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var array
     */
    protected $post = [
        'current_password' => 'secret',
        'password' => 'password',
        'password_confirmation' => 'password'
    ];

    /**
     * @var string
     */
    protected $url;

    /**
     * @group Account
     * @group acceptance
     * @test
     */
    public function the_current_password_is_required()
    {
        $this->post['current_password'] = '';
        $this->sendResponse()->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'current_password' => ['The current password field is required.']
            ]
        ], 422);
    }

    /**
     * @group Account
     * @group acceptance
     * @test
     */
    public function a_user_password_must_be_correct()
    {
        $user = create(User::class, ['password' => 'secret']);
        $this->post['current_password'] = 'password';
        $this->sendResponse($user)->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'current_password' => ['Your current password is incorrect!']
            ]
        ], 422);
    }

    /**
     * @group Account
     * @group acceptance
     * @test
     */
    public function a_new_password_is_required()
    {
        $this->post['password'] = '';
        $this->sendResponse()->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'password' => ['The password field is required.']
            ]
        ], 422);
    }

    /**
     * @group Account
     * @group acceptance
     * @test
     */
    public function the_new_password_and_confirmed_password_must_match()
    {
        $this->post['password'] = 'not_password';
        $this->sendResponse()->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'password' => ['The password confirmation does not match.']
            ]
        ], 422);
    }

    /**
     * @param User|null $user
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function sendResponse(User $user = null)
    {
        $this->url = route('profile.password');
        return $this->signIn($user)->json('post', $this->url, $this->post);
    }

}
