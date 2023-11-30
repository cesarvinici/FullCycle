<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\DTO\ListCastMember\ListCastMemberInputDto;
use Core\UseCase\CastMember\DTO\ListCastMember\ListCastMemberOutputDto;
use Core\UseCase\CastMember\ListCastMemberUseCase;
use Core\UseCase\Category\ListCategoryUseCase;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;
use Mockery;

class ListCastMemberUseCaseUnitTest extends TestCase
{
    private $mockCastMemberRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockCastMemberRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testFindById()
    {
        $uuid = (string) Uuid::uuid4();

        $mockEntity = Mockery::mock(
            CastMember::class,
            ["Cast Member Name", CastMemberType::DIRECTOR , $uuid]
        );

        $mockEntity->shouldReceive("id")->andReturn($uuid);
        $mockEntity->shouldReceive("createdAt")->andReturn(date("Y-m-d H:i:s"));
        $mockEntity->shouldReceive("updatedAt")->andReturn(date("Y-m-d H:i:s"));

        $this->mockCastMemberRepository->shouldReceive('findById')
            ->once()
            ->andReturn($mockEntity);

        $mockInputDto = Mockery::mock(ListCastMemberInputDto::class, [$uuid]);

        $mockOutputDto = Mockery::mock(ListCastMemberOutputDto::class,
            [
                $uuid,
                "Cast Member Name",
                CastMemberType::DIRECTOR->value,
                date("Y-m-d H:i:s"),
                date("Y-m-d H:i:s"),
            ]
        );

        $useCase = new ListCastMemberUseCase($this->mockCastMemberRepository);
        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(ListCastMemberOutputDto::class, $response);
        $this->assertEquals("Cast Member Name", $response->name);
        $this->assertEquals(CastMemberType::DIRECTOR->value, $response->type);
        $this->assertNotNull($response->createdAt);
        $this->assertNotNull($response->updatedAt);
    }
}
