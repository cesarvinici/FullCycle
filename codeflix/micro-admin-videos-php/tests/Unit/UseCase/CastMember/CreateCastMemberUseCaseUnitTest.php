<?php

namespace Tests\Unit\UseCase\CastMember;

use App\Models\Genre;
use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\CreateCastMemberUseCase;
use Core\UseCase\CastMember\DTO\Create\CreateCastMemberInputDto;
use Core\UseCase\CastMember\DTO\Create\CreateCastMemberOutputDto;
use PHPUnit\Framework\TestCase;
use Mockery;
use Ramsey\Uuid\Uuid;
use stdClass;

class CreateCastMemberUseCaseUnitTest extends TestCase
{
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

    public function testCreateCastMember()
    {
        $uuid = (string) Uuid::uuid4();

        $mockEntity = Mockery::mock(
            CastMember::class,
            ["Cast Member Name", CastMemberType::DIRECTOR , $uuid]
        );

        $mockEntity->shouldReceive("id")->andReturn($uuid);
        $mockEntity->shouldReceive("createdAt")->andReturn(date("Y-m-d H:i:s"));
        $mockEntity->shouldReceive("updatedAt")->andReturn(date("Y-m-d H:i:s"));

        $this->mockCastMemberRepository->shouldReceive('insert')
            ->once()
            ->andReturn($mockEntity);

        $mockInputDto = Mockery::mock(CreateCastMemberInputDto::class,
            [
                "Cast Member Name",
                1,
            ]
        );

        $mockOutputDto = Mockery::mock(CreateCastMemberOutputDto::class,
            [
                $uuid,
                "Cast Member Name",
                1,
                date("Y-m-d H:i:s"),
                date("Y-m-d H:i:s"),
            ]
        );

        $useCase = new CreateCastMemberUseCase($this->mockCastMemberRepository);
        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(CreateCastMemberOutputDto::class, $response);
        $this->assertEquals("Cast Member Name", $response->name);
        $this->assertEquals(1, $response->type);
        $this->assertEquals($uuid, $response->id);
        $this->assertNotNull($response->created_at);
        $this->assertNotNull($response->updated_at);
    }

}
