<?php

namespace Tests\Unit\Entities;

use App\Entities\Admin;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    use ModelTestTrait;
    use RefreshDatabase;

    public function testFactoryable()
    {
        $this->assertFactoriable(Admin::class);
    }
}
