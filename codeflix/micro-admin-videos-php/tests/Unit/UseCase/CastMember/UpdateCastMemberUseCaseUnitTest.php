<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\UseCase\CastMember\DTO\Update\UpdateCastMemberInputDto;
use Core\UseCase\CastMember\DTO\Update\UpdateCastMemberOutputDto;
use Core\UseCase\CastMember\UpdateCastMemberUseCase;
use PHPUnit\Framework\TestCase;
use Mockery;
use Ramsey\Uuid\Uuid;

class UpdateCastMemberUseCaseUnitTest extends TestCase
{

    public function testUpdateCastMember()
    {
        $casMemberName = "Cast Member Name";
        $id = (string) Uuid::uuid4();
        $type = CastMemberType::DIRECTOR;

        $editedCastMemberName = "Edited Cast Member Name";
        $editedType = CastMemberType::ACTOR;

        $mockCastMemberEntity = Mockery::mock(
            CastMember::class,
            [$editedCastMemberName, $editedType, new ValueObjectUuid($id)]
        );

        $mockCastMemberEntity->shouldReceive('update');
        $mockCastMemberEntity->shouldReceive("id")->andReturn($id);
        $mockCastMemberEntity->shouldReceive("createdAt")->andReturn(date("Y-m-d H:i:s"));
        $mockCastMemberEntity->shouldReceive("updatedAt")->andReturn(date("Y-m-d H:i:s"));

        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepository->shouldReceive('findById')
            ->once()
            ->with($id)
            ->andReturn($mockCastMemberEntity);

        $mockRepository->shouldReceive('update')->once()->andReturn($mockCastMemberEntity);

        $mockInputDto = Mockery::mock(
            UpdateCastMemberInputDto::class,
            [
                $id,
                $editedCastMemberName,
                $editedType->value
            ]
        );

        $useCase = new UpdateCastMemberUseCase($mockRepository);
        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(UpdateCastMemberOutputDto::class, $response);
        $this->assertEquals($id, $response->id);
        $this->assertEquals($editedCastMemberName, $response->name);
        $this->assertEquals($editedType->value, $response->type);
        $this->assertEquals($mockCastMemberEntity->createdAt(), $response->created_at);
        $this->assertEquals($mockCastMemberEntity->updatedAt(), $response->updated_at);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
