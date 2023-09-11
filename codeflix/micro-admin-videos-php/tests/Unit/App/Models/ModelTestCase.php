<?php

namespace Tests\Unit\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

abstract class ModelTestCase extends TestCase
{
    abstract protected function model(): Model;
    abstract protected function traits(): array;
    abstract protected function fillables(): array;
    abstract protected function casts(): array;

    public function testIfUseTraits()
    {
        $neededTraits = $this->traits();

        $traits = array_keys(class_uses($this->model()));

        $this->assertEquals($neededTraits, $traits);
    }

    public function testIncrementingIsFalse()
    {
        $model = $this->model();
        $this->assertFalse($model->incrementing);
    }

    public function testHasCastsAttributes()
    {
        $model = $this->model();
        $this->assertEqualsCanonicalizing(
            $this->casts(),
            $model->getCasts()
        );
    }

    public function testFillableAttributes()
    {
        $model = $this->model();
        $this->assertEqualsCanonicalizing(
            $this->fillables(),
            $model->getFillable()
        );
    }

}
