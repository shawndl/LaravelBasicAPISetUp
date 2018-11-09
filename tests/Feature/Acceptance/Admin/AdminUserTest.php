<?php

namespace Tests\Feature\Acceptance\Admin;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @group acceptance
     * @group admin
     * @test
     */
    public function only_administrators_can_access_all_users()
    {
        $this->signIn()->json('get', route('admin.user.index'))
            ->assertStatus(403);
    }

    /**
     * @group acceptance
     * @group admin
     * @test
     */
    public function only_administrators_can_access_a_single_user()
    {
        $user = create(User::class, ['name' => 'tom']);
        $this->signIn()->json('get', route('admin.user.show', ['name' => $user->url()]))
            ->assertStatus(403);
    }

    /**
     * @group acceptance
     * @group admin
     * @test
     */
    public function an_admin_can_view_all_users()
    {
        create(User::class, [], 25);
        $this->signInAdmin()->json('get', route('admin.user.index'))
            ->assertJsonStructure([
                'data' => [['id', 'name', 'email', 'is_admin']],
                'meta' => ['current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'],
                'links' => ['first', 'last', 'prev', 'next']
            ]);

    }

    /**
     * @group acceptance
     * @group admin
     * @test
     */
    public function an_admin_can_view_information_about_a_single_user()
    {
        $user = create(User::class, ['name' => 'tom']);

        $this->signInAdmin()
            ->json('get', route('admin.user.show', ['user' => $user->url()]))
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' =>  $user->email,
                    'is_admin' => false
                ]
        ]);
    }
}
