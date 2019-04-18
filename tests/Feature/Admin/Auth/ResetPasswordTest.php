<?php

namespace Tests\Feature\Admin\Auth;

use App\Entities\Admin;
use App\Notifications\Admin\ResetPasswordNotification;
use Auth;
use Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function testCanSeeIndex()
    {
        $response = $this->get('/admin/password/reset');
        $response->assertOk()
            ->assertSee('name="email"');
    }

    public function testCanResetPassword()
    {
        Notification::fake();
        $admin = factory(Admin::class)->create();
        $response = $this->from('/admin/password/reset')->post('/admin/password/email', [
            'email' => $admin->email,
        ]);
        $token = '';
        Notification::assertSentTo(
            $admin,
            ResetPasswordNotification::class,
            function ($notification, $channels) use ($admin, &$token) {
                $token = $notification->token;
                return true;
            }
        );

        $response = $this->get('/admin/password/reset/' . $token);
        $response->assertOk()
            ->assertSee('name="token"')
            ->assertSee('name="email"')
            ->assertSee('name="password"')
            ->assertSee('name="password_confirmation"');

        $new = Str::random(10);
        $response = $this->post('/admin/password/reset', [
            'token' => $token,
            'email' => $admin->email,
            'password' => $new,
            'password_confirmation' => $new,
        ]);

        $response->assertRedirect('/admin');

        $this->assertTrue(Auth::guard('admin')->check());
        $this->assertTrue(Hash::check($new, $admin->fresh()->password));
    }
}
