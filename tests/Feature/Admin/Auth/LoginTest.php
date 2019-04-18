<?php

namespace Tests\Feature\Admin\Auth;

use App\Entities\Admin;
use Auth;
use Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testCanSeeIndex()
    {
        $response = $this->get('/admin/login');
        $response->assertOk()
            ->assertSee('href="/admin/password/reset')
            ->assertSee('name="email"')
            ->assertSee('name="password"')
            ->assertSee('name="remember"');
    }

    public function testCanLogin()
    {
        $password = Str::random(10);
        $admin = factory(Admin::class)->create([
            'password' => Hash::make($password),
        ]);
        $response = $this->from('/admin/login')->post('/admin/login', [
            'email' => $admin->email,
            'password' => $password,
        ]);
        $response->assertRedirect('/admin');
    }

    public function testLoginFails()
    {
        $password = Str::random(10);
        $admin = factory(Admin::class)->create([
            'password' => Hash::make($password),
        ]);
        $response = $this->from('/admin/login')->post('/admin/login', [
            'email' => $admin->email,
            'password' => $password . 'invalid',
        ]);
        $response->assertRedirect('/admin/login');
    }

    public function testCanLogout()
    {
        $admin = factory(Admin::class)->create();
        $this->actingAs($admin, 'admin');
        $response = $this->get('/admin/logout');
        $response->assertRedirect('/admin/login');
        $this->assertFalse(Auth::guard('admin')->check());
    }
}
