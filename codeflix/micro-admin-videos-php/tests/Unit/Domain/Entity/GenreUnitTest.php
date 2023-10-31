<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Genre;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Datetime;

class GenreUnitTest extends TestCase
{
    public function testAttributes()
    {
        $uuid = (string) RamseyUuid::uuid4();
        $createdAt = new Datetime(date('Y-m-d H:i:s'));

        $genre = new Genre(
            id: new Uuid($uuid),
            name: 'Genre Name',
            isActive: true,
            createdAt: $createdAt,
        );

        $this->assertEquals($uuid, $genre->id());
        $this->assertEquals('Genre Name', $genre->name);
        $this->assertTrue($genre->isActive);
        $this->assertEquals($createdAt->format('Y-m-d H:i:s'), $genre->createdAt());
    }

    public function testAttributesCreated()
    {

        $genre = new Genre(
            name: 'Genre Name',
        );

        $this->assertNotEmpty($genre->id());
        $this->assertEquals('Genre Name', $genre->name);
        $this->assertTrue($genre->isActive);
        $this->assertNotEmpty($genre->createdAt());
    }

    public function testDeactivate()
    {
        $genre = new Genre(
            name: 'Genre Name',
        );

        $this->assertTrue($genre->isActive);
        $genre->deactivate();
        $this->assertFalse($genre->isActive);
    }

    public function testActivate()
    {
        $genre = new Genre(
            name: 'Genre Name',
            isActive: false,
        );

        $this->assertFalse($genre->isActive);
        $genre->activate();
        $this->assertTrue($genre->isActive);
    }

    public function testUpdate()
    {
        $genre = new Genre(
            name: 'Genre Name',
        );

        $this->assertEquals('Genre Name', $genre->name);

        $genre->update(
            name: 'Genre Name Updated',
        );

        $this->assertEquals('Genre Name Updated', $genre->name);
    }

    public function testEntityExceptionOnCreate()
    {
        $this->expectException(EntityValidationException::class);

        $genre = new Genre(
            name: 'G',
        );
    }

    public function testExceptionOnUpdate()
    {
        $genre = new Genre(
            name: 'Genre Name',
        );

        $this->expectException(EntityValidationException::class);
        $genre->update(
            name: 'G',
        );
    }

    public function testAddCategoryToGenre()
    {
        $categoryId = (string) RamseyUuid::uuid4();

        $genre = new Genre(
            name: 'Genre Name',
        );

        $this->assertEmpty($genre->categoriesId);
        $genre->addCategory($categoryId);

        $this->assertCount(1, $genre->categoriesId);
    }

    public function testRemoveCategory()
    {
        $categoryId = (string) RamseyUuid::uuid4();
        $categoryId2 = (string) RamseyUuid::uuid4();

        $genre = new Genre(
            name: 'Genre Name',
            categoriesId: [$categoryId, $categoryId2],
        );

        $this->assertCount(2, $genre->categoriesId);
        $genre->removeCategory($categoryId);
        $this->assertCount(1, $genre->categoriesId);

    }
}
