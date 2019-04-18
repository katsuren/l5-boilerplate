<?php

namespace Tests\Feature\Admin;

use App\Entities\Admin;
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
        $admin = factory(Admin::class)->create();
        $this->actingAs($admin, 'admin');
        $response = $this->get('/admin/account');
        $response->assertOk()
            ->assertSee('action="/admin/account"')
            ->assertSee('name="admin[name]"')
            ->assertSee('name="admin[email]"')
            ->assertSee('name="admin[password]"')
            ->assertSee('name="admin[password_confirmation]"');
    }

    public function testCanUpdateAccount()
    {
        $admin = factory(Admin::class)->create();
        $this->actingAs($admin, 'admin');
        $newAdmin = factory(Admin::class)->make();
        $newEmail = $admin->email . 'new';
        $newPassword = Str::random(10);
        $response = $this->from('/admin/account')->put('/admin/account', [
            'admin' => [
                'name' => $newAdmin->name,
                'email' => $newEmail,
                'password' => $newPassword,
                'password_confirmation' => $newPassword,
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('admins', [
            'id' => $admin->id,
            'name' => $newAdmin->name,
            'email' => $newEmail,
        ]);
        $this->assertTrue(app('hash')->check($newPassword, $admin->refresh()->password));
    }
}
