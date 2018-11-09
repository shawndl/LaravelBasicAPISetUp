<?php

namespace Tests\Feature\acceptance\user\profile;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserProfilePageValidationTest extends TestCase
{
    use RefreshDatabase, \ValidationTestTrait;

    protected $post = [
        'name' => 'tom',
        'email' => 'tom@gmail.com',
    ];

    /**
     * @group account
     * @group acceptance
     * @test
     */
    public function a_profile_name_is_required()
    {
        $this->post['name'] = '';
        $this->sendResponse()->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'name' => ['The name field is required.']
            ]
        ], 422);
    }

    /**
     * @group account
     * @group acceptance
     * @test
     */
    public function a_profile_name_must_be_unique()
    {
        create(User::class, ['name' => 'candy']);
        $this->post['name'] = 'candy';
        $this->sendResponse()->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'name' => ['The name has already been taken.']
            ]
        ], 422);
    }


    /**
     * @group account
     * @group acceptance
     * @test
     */
    public function a_profile_email_is_required()
    {
        $this->post['email'] = '';
        $this->sendResponse()->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'email' => ['The email field is required.']
            ]
        ], 422);
    }

    /**
     * @group account
     * @group acceptance
     * @test
     */
    public function an_email_must_be_an_email()
    {
        $this->post['email'] = 'email';
        $this->sendResponse()->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'email' => ['The email must be a valid email address.']
            ]
        ], 422);
    }

    /**
     * @group account
     * @group acceptance
     * @test
     */
    public function an_email_must_be_unique()
    {
        create(User::class, ['email' => 'taken@company.com']);
        $this->post['email'] = 'taken@company.com';
        $this->sendResponse()->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'email' => ['The email has already been taken.']
            ]
        ], 422);
    }

    /**
     * @param User|null $user
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function sendResponse(User $user = null)
    {
        $url = route('profile.update');
        return $this->signIn($user)->json('post', $url, $this->post);
    }
}
