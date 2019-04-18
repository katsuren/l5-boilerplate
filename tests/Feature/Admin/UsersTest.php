<?php

namespace Tests\Feature\Admin;

use App\Entities\Admin;
use App\Entities\User;
use Auth;
use Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    public function testCanSeeIndex()
    {
        $admin = factory(Admin::class)->create();
        $this->actingAs($admin, 'admin');
        $response = $this->get('/admin/users');
        $response->assertOk()
            ->assertSee('action="/admin/users"')
            ->assertSee('name="s[name]"')
            ->assertSee('name="s[email]"');

        // ユーザーが存在する場合もやっておく
        $users = factory(User::class, 100)->create();
        $response = $this->get('/admin/users');
        $response->assertOk()
            ->assertSee('action="/admin/users"')
            ->assertSee('name="s[name]"')
            ->assertSee('name="s[email]"');
    }

    public function testCanUpdateUser()
    {
        $admin = factory(Admin::class)->create();
        $this->actingAs($admin, 'admin');
        $user = factory(User::class)->create();

        $response = $this->get('/admin/users/' . $user->id . '/edit');
        $response->assertOk()
            ->assertSee('action="/admin/users/' . $user->id .'"')
            ->assertSee('name="user[name]"')
            ->assertSee('name="user[email]"');

        $newUser = factory(User::class)->make();
        $response = $this->from('/admin/users/' . $user->id . '/edit')->put('/admin/users/' . $user->id, [
            'user' => [
                'name' => $newUser->name,
                'email' => $newUser->email,
            ],
        ]);
        $response->assertRedirect();

        $this->assertDatabaseHas('users', ['name' => $newUser->name, 'email' => $newUser->email]);
    }

    public function testCanDestroyUser()
    {
        $admin = factory(Admin::class)->create();
        $this->actingAs($admin, 'admin');
        $user = factory(User::class)->create();
        $response = $this->from('/admin/users/' . $user->id . '/edit')->delete('/admin/users/' . $user->id);
        $response->assertRedirect();
        $this->assertDatabaseMissing('users', ['name' => $user->name, 'email' => $user->email]);
    }
}
