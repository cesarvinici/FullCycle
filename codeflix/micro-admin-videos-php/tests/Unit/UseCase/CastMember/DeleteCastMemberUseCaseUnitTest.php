<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\DeleteCastMemberUseCase;
use Core\UseCase\CastMember\DTO\Delete\DeleteCastMemberInputDto;
use Core\UseCase\CastMember\DTO\Delete\DeleteCastMemberOutputDto;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;
use Mockery;

class DeleteCastMemberUseCaseUnitTest extends TestCase
{
    public function testDeleteCastMember()
    {
        $castMemberName = "Cast Member Name";
        $type = CastMemberType::DIRECTOR;
        $id = Uuid::uuid4()->toString();

        $mockCastMember = Mockery::mock(CastMember::class, [$castMemberName, $type, $id]);

        $inputDto = Mockery::mock(
            DeleteCastMemberInputDto::class,
            [
                $id
            ]
        );

        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepository->shouldReceive('findById')
            ->once()
            ->with($inputDto->id)
            ->andReturn($mockCastMember);
        $mockRepository->shouldReceive('delete')
            ->once()
            ->with($inputDto->id)
            ->andReturn(true);

        $useCase = new DeleteCastMemberUseCase($mockRepository);
        $response = $useCase->execute($inputDto);

        $this->assertInstanceOf(DeleteCastMemberOutputDto::class, $response);
        $this->assertTrue($response->success);
    }
}
