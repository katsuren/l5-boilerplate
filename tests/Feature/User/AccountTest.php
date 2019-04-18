<?php

namespace Tests\Feature\User;

use App\Entities\User;
use App\Notifications\VerifyUpdateEmailNotification;
use Auth;
use Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function testCanSeeIndex()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->get('/user/account');
        $response->assertOk()
            ->assertSee('action="/user/account"')
            ->assertSee('name="user[name]"')
            ->assertSee('name="user[email]"')
            ->assertSee('name="user[password]"')
            ->assertSee('name="user[password_confirmation]"');
    }

    public function testCanUpdateAccount()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $newUser = factory(User::class)->make();
        $newPassword = Str::random(10);
        $response = $this->from('/user/account')->put('/user/account', [
            'user' => [
                'name' => $newUser->name,
                'email' => $user->email,
                'password' => $newPassword,
                'password_confirmation' => $newPassword,
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $newUser->name,
        ]);
        $this->assertTrue(app('hash')->check($newPassword, $user->refresh()->password));
    }

    public function testCanUpdateEmail()
    {
        Notification::fake();
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $email = $user->email . '2';
        $response = $this->from('/user/account')->put('/user/account', [
            'user' => [
                'name' => $user->name,
                'email' => $email,
            ],
        ]);
        // この時点では変わっていないこと
        $this->assertNotEquals($user->email, $email);

        $url = '';
        Notification::assertSentTo(
            new AnonymousNotifiable,
            VerifyUpdateEmailNotification::class,
            function ($notification, $channels, $notifiable) use ($email, &$url) {
                $url = $notification->toMail($notifiable)->actionUrl;
                return $notifiable->routes['mail'] === $email;
            }
        );

        $response = $this->get($url);
        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $email,
        ]);
    }
}
