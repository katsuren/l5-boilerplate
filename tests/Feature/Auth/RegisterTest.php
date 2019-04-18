<?php

namespace Tests\Feature\Auth;

use App\Entities\User;
use App\Notifications\VerifyEmailNotification;
use Auth;
use Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function testCanSeeIndex()
    {
        $response = $this->get('/register');
        $response->assertOk()
            ->assertSee('name="name"')
            ->assertSee('name="email"')
            ->assertSee('name="password"')
            ->assertSee('name="password_confirmation"');
    }

    public function testCanResisterAndVerify()
    {
        Notification::fake();
        $pass = Str::random(10);
        $user = factory(User::class)->make(['password' => Hash::make($pass)]);
        $response = $this->from('/register')->post('/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $pass,
            'password_confirmation' => $pass,
        ]);
        $response->assertRedirect('/email/verify');

        $url = '';
        $user = User::where(['name' => $user->name, 'email' => $user->email])->first();
        Notification::assertSentTo(
            $user,
            VerifyEmailNotification::class,
            function ($notification, $channels) use ($user, &$url) {
                $url = $notification->toMail($user)->actionUrl;
                return true;
            }
        );

        $response = $this->get('/email/verify')
            ->assertOk()
            ->assertSee('/email/resend');

        $response = $this->get($url)
            ->assertRedirect('/home');

        $this->assertTrue(Auth::check());
        $this->assertTrue(Hash::check($pass, $user->fresh()->password));
    }

    public function testVerifyUrlRedirectsWithoutSession()
    {
        $this->get('/email/verify')->assertRedirect('/login');
    }

    public function testCanResendVerifyEmail()
    {
        Notification::fake();
        $pass = Str::random(10);
        $user = factory(User::class)->make(['password' => Hash::make($pass)]);
        $this->from('/register')->post('/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $pass,
            'password_confirmation' => $pass,
        ]);

        $response = $this->get('/email/resend');
        $response->assertRedirect('/register');
        $user = User::where(['name' => $user->name, 'email' => $user->email])->first();
        Notification::assertSentTo(
            $user,
            VerifyEmailNotification::class,
            2
        );
    }
}
