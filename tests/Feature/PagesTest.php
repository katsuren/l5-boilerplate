<?php

namespace Tests\Feature;

use Auth;
use Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class PagesTest extends TestCase
{
    use RefreshDatabase;

    public function testCanSeeExistingPage()
    {
        $response = $this->get('/pages/welcome');
        $response->assertOk();
    }

    public function testCannotSeeNotExistingPage()
    {
        $response = $this->get('/pages/hoge');
        $response->assertNotFound();
    }
}
