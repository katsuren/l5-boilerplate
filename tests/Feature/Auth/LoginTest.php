<?php

namespace Tests\Feature\Auth;

use App\Entities\User;
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
        $response = $this->get('/login');
        $response->assertOk()
            ->assertSee('href="/password/reset')
            ->assertSee('name="email"')
            ->assertSee('name="password"')
            ->assertSee('name="remember"');
    }

    public function testCanLogin()
    {
        $password = Str::random(10);
        $user = factory(User::class)->create([
            'password' => Hash::make($password),
        ]);
        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);
        $response->assertRedirect('/home');
    }

    public function testLoginFails()
    {
        $password = Str::random(10);
        $user = factory(User::class)->create([
            'password' => Hash::make($password),
        ]);
        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => $password . 'invalid',
        ]);
        $response->assertRedirect('/login');
    }

    public function testCanLogout()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->get('/logout');
        $response->assertRedirect('/');
        $this->assertFalse(Auth::check());
    }
}
