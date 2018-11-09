<?php

namespace Tests\Feature\Integration\Models\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp()
    {
        parent::setUp();
        $this->user = create(User::class);
    }

    /**
     * @group integration
     * @group model
     * @group auth
     * @test
     */
    public function it_must_be_able_to_find_a_user_by_email()
    {
        $user = User::email($this->user->email);
        $this->assertEquals($user->id, $this->user->id);
    }

    /**
     * @group integration
     * @group model
     * @group auth
     * @test
     */
    public function it_must_be_able_to_find_a_user_by_name()
    {
        $user = User::name($this->user->name);
        $this->assertEquals($user->id, $this->user->id);
    }

    /**
     * @group integration
     * @group model
     * @group auth
     * @test
     */
    public function it_must_be_able_to_find_a_user_by_email_or_name()
    {
        $user = User::nameEmail($this->user->name);
        $this->assertEquals($user->id, $this->user->id);
    }

    /**
     * @group integration
     * @group model
     * @group auth
     * @test
     */
    public function a_user_can_be_banned_from_the_site()
    {
        $this->user->banned();
        $this->assertTrue($this->user->banned);

        $this->assertDatabaseHas('users', [
            'banned' => 1
        ]);
    }
}
