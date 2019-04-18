<?php

namespace Tests\Unit\Entities;

trait ModelTestTrait
{
    public function assertFactoriable($class)
    {
        $model = app($class);
        $this->assertEmpty($model->get());
        factory($class)->create();
        $this->assertNotEmpty($model->get());
    }

    public function assertBelongsTo($parentClass, $childClass, $foreignKey, $property)
    {
        $parent = factory($parentClass)->create()->first();
        $child = factory($childClass)->create([
            $foreignKey => $parent->id,
        ])->first();
        $this->assertNotEmpty($child->{$property});
    }

    public function assertHasMany($parentClass, $childClass, $foreignKey, $property)
    {
        $parent = factory($parentClass)->create()->first();
        $children = factory($childClass, 5)->create([
            $foreignKey => $parent->id,
        ]);
        $this->assertNotEmpty($parent->{$property});
    }
}
