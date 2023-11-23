<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Datetime;

class CastMemberUnitTest extends TestCase
{
    public function testAttributes()
    {
        $uuid = (string) RamseyUuid::uuid4();
        $createdAt = new Datetime(date('Y-m-d H:i:s'));
        $updatedAt = new Datetime(date('Y-m-d H:i:s'));

        $castMember = new CastMember(
            name: 'Cast Member Name',
            type: CastMemberType::ACTOR,
            id: new Uuid($uuid),
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );

        $this->assertEquals($uuid, $castMember->id());
        $this->assertEquals('Cast Member Name', $castMember->name);
        $this->assertEquals(CastMemberType::ACTOR, $castMember->type);
        $this->assertEquals($createdAt->format('Y-m-d H:i:s'), $castMember->createdAt());
        $this->assertEquals($updatedAt->format('Y-m-d H:i:s'), $castMember->updatedAt());
    }

    public function testAttributesWithoutId()
    {
        $createdAt = new Datetime(date('Y-m-d H:i:s'));
        $updatedAt = new Datetime(date('Y-m-d H:i:s'));

        $castMember = new CastMember(
            name: 'Cast Member Name',
            type: CastMemberType::ACTOR,
        );

        $this->assertNotEmpty($castMember->id());
    }

    public function testAttributesWithoutName()
    {
        $this->expectException(EntityValidationException::class);
        $castMember = new CastMember(
            name: "G",
            type: CastMemberType::ACTOR,
        );

        $this->assertNotEmpty($castMember->id());
    }

    public function testUpdate()
    {
        $castMember = new CastMember(
            name: 'Cast Member Name',
            type: CastMemberType::ACTOR,
        );

        $castMember->update(
            name: 'Cast Member Name Updated',
            type: CastMemberType::DIRECTOR,
        );

        $this->assertEquals('Cast Member Name Updated', $castMember->name);
        $this->assertEquals(CastMemberType::DIRECTOR, $castMember->type);
    }

    public function testUpdateInvalidName()
    {
        $castMember = new CastMember(
            name: 'Cast Member Name',
            type: CastMemberType::ACTOR,
        );

        $this->expectException(EntityValidationException::class);

        $castMember->update(
            name: 'g',
            type: CastMemberType::DIRECTOR,
        );
    }
}
