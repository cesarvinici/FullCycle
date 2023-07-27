<?php

namespace Tests\Unit\Domain\Entity;


use Core\Domain\Entity\Category;
use Core\Domain\Exception\EntityValidationException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CategoryUnitTest extends TestCase
{
    public function testAttributes()
    {
        $category = new Category(
            name: 'Category Test',
            description: 'Description Test',
            isActive: true
        );

        $this->assertNotEmpty($category->id());
        $this->assertEquals('Category Test', $category->name);
        $this->assertEquals('Description Test', $category->description);
        $this->assertTrue($category->isActive);
        $this->assertNotEmpty($category->createdAt());
        $this->assertNotEmpty($category->updatedAt());
    }

    public function testActivated()
    {
        $category = new Category(
            name: 'Category Test',
            isActive: false
        );

        $this->assertFalse($category->isActive);

        $category->activate();

        $this->assertTrue($category->isActive);
    }

    public function testDisabled()
    {
        $category = new Category(
            name: 'Category Test',
        );

        $this->assertTrue($category->isActive);

        $category->disable();

        $this->assertFalse($category->isActive);
    }

    public function testUpdate()
    {

        $uuid = (string) Uuid::uuid4()->toString();

        $category = new Category(
            id: $uuid,
            name: 'Category Test',
            description: 'Description Test',
            isActive: true,
            createdAt: "2023-01-01 00:00:00",
            updatedAt: "2023-01-01 00:00:00",
        );

        $category->update(
            name: 'Category Test Update',
            description: 'Description Test Update',
        );

        $this->assertEquals('Category Test Update', $category->name);
        $this->assertEquals('Description Test Update', $category->description);
        $this->assertEquals($uuid, $category->id());
        $this->assertNotEquals("2023-01-01 00:00:00", $category->updatedAt());
    }

    public function testExceptionName()
    {
        try {
            $category = new Category(
                name: 'Ne',
                description: 'Description Test',
            );

            $this->assertTrue(false);

        } catch (\Throwable $throwable) {
            $this->assertInstanceOf(EntityValidationException::class, $throwable);
        }
    }

    public function testExceptionDescription()
    {
        try {
            $category = new Category(
                name: 'Name Test',
                description: random_bytes(256),
            );

            $this->assertTrue(false);

        } catch (\Throwable $throwable) {
            $this->assertInstanceOf(EntityValidationException::class, $throwable);
        }
    }


}