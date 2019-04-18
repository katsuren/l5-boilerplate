<?php

namespace Tests\Unit\Entities;

use App\Entities\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use ModelTestTrait;
    use RefreshDatabase;

    public function testFactoryable()
    {
        $this->assertFactoriable(User::class);
    }
}
