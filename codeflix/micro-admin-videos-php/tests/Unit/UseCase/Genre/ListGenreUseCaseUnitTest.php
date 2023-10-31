<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\Genre\DTO\ListGenre\ListGenreInputDto;
use Core\UseCase\Genre\DTO\ListGenre\ListGenreOutputDto;
use Core\UseCase\Genre\ListGenresUseCase;
use Core\UseCase\Genre\ListGenreUseCase;
use Mockery;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class ListGenreUseCaseUnitTest extends TestCase
{
    public function testListSingle()
    {
        $uuid = (string) Uuid::uuid4();
        $mockEntity = Mockery::mock(
            Genre::class,
            ["Genre Name", $uuid, true, []]
        );

        $mockEntity->shouldReceive("id")->andReturn($uuid);
        $mockEntity->shouldReceive("name")->andReturn("Genre Name");
        $mockEntity->shouldReceive("isActive")->andReturn(true);
        $mockEntity->shouldReceive("categoriesId")->andReturn([]);
        $mockEntity->shouldReceive("createdAt")->andReturn(date("Y-m-d H:i:s"));

        $mockRepository = Mockery::mock(\stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('findById')
            ->once()
            ->andReturn($mockEntity);

        $inputDto = Mockery::mock(ListGenreInputDto::class, [$uuid]);

        $useCase = new ListGenreUseCase($mockRepository);
        $response = $useCase->execute($inputDto);

        $this->assertInstanceOf(ListGenreOutputDto::class, $response);
        $this->assertEquals($uuid, $response->id);
        $this->assertEquals("Genre Name", $response->name);
        $this->assertTrue($response->is_active);
        $this->assertEquals([], $response->categoriesId);
        $this->assertNotNull($response->created_at);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
