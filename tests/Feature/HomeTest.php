<?php

namespace Tests\Feature;

use App\Entities\User;
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
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->get('/');
        $response->assertOk()
            ->assertSee($user->name);
    }
}
