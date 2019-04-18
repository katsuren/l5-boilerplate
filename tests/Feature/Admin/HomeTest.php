<?php

namespace Tests\Feature\Admin;

use App\Entities\Admin;
use Auth;
use Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function testCanSeeIndex()
    {
        $admin = factory(Admin::class)->create();
        $this->actingAs($admin, 'admin');
        $response = $this->get('/admin');
        $response->assertOk()
            ->assertSee($admin->name);
    }
}
