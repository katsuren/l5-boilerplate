<?php

namespace Tests\Feature\Admin;

use App\Entities\Admin;
use App\Notifications\Admin\RegisterNotification;
use Auth;
use Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class AdminsTest extends TestCase
{
    use RefreshDatabase;

    public function testCanSeeIndex()
    {
        $admin = factory(Admin::class)->create();
        $this->actingAs($admin, 'admin');
        $response = $this->get('/admin/admins');
        $response->assertOk()
            ->assertSee('action="/admin/admins"')
            ->assertSee('name="s[name]"')
            ->assertSee('name="s[email]"');
    }

    public function testCanCreateAndDestroyAdmin()
    {
        $admin = factory(Admin::class)->create();
        $this->actingAs($admin, 'admin');
        $response = $this->get('/admin/admins/create');
        $response->assertOk()
            ->assertSee('action="/admin/admins"')
            ->assertSee('name="admin[name]"')
            ->assertSee('name="admin[email]"');

        $email = time() . '@example.net';
        $tmp = factory(Admin::class)->make(['email' => $email]);

        Notification::fake();
        $response = $this->from('/admin/admins/create')->post('/admin/admins', [
            'admin' => [
                'name' => $tmp->name,
                'email' => $tmp->email,
            ],
        ]);
        $response->assertRedirect();

        $password = '';
        $newAdmin = Admin::where('email', $email)->first();
        Notification::assertSentTo(
            $newAdmin,
            RegisterNotification::class,
            function ($notification, $channels) use ($newAdmin, &$password) {
                $notification->toMail($newAdmin)->actionUrl;
                $password = $notification->getPassword();
                return true;
            }
        );

        $this->assertDatabaseHas('admins', ['name' => $newAdmin->name, 'email' => $newAdmin->email]);
        $this->assertTrue(app('hash')->check($password, $newAdmin->password));

        $response = $this->from('/admin/admins')->delete('/admin/admins/' . $newAdmin->id);
        $response->assertRedirect();

        $this->assertDatabaseMissing('admins', ['name' => $newAdmin->name, 'email' => $newAdmin->email]);
    }
}
