<?php

namespace Tests\Feature\Auth;

use App\Entities\User;
use App\Notifications\ResetPasswordNotification;
use Auth;
use Hash;
use Tests\TestCase;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function testCanSeeIndex()
    {
        $response = $this->get('/password/reset');
        $response->assertOk()
            ->assertSee('name="email"');
    }

    public function testCanResetPassword()
    {
        Notification::fake();
        $user = factory(User::class)->create();
        $response = $this->from('/password/reset')->post('/password/email', [
            'email' => $user->email,
        ]);
        $token = '';
        Notification::assertSentTo(
            $user,
            ResetPasswordNotification::class,
            function ($notification, $channels) use ($user, &$token) {
                $token = $notification->token;
                return true;
            }
        );

        $response = $this->get('/password/reset/' . $token);
        $response->assertOk()
            ->assertSee('name="token"')
            ->assertSee('name="email"')
            ->assertSee('name="password"')
            ->assertSee('name="password_confirmation"');

        $new = Str::random(10);
        $response = $this->post('/password/reset', [
            'token' => $token,
            'email' => $user->email,
            'password' => $new,
            'password_confirmation' => $new,
        ]);

        $response->assertRedirect('/home');

        $this->assertTrue(Auth::check());
        $this->assertTrue(Hash::check($new, $user->fresh()->password));
    }
}
