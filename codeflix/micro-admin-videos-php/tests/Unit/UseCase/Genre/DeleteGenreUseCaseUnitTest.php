<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\Genre\DeleteGenreUseCase;
use Core\UseCase\Genre\DTO\DeleteGenreInputDto;
use Core\UseCase\Genre\DTO\ListGenre\ListGenreInputDto;
use Core\UseCase\Genre\DTO\ListGenre\ListGenreOutputDto;
use Core\UseCase\Genre\DTO\Update\DeleteGenreOutputDto;
use Core\UseCase\Genre\ListGenresUseCase;
use Core\UseCase\Genre\ListGenreUseCase;
use Mockery;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class DeleteGenreUseCaseUnitTest extends TestCase
{
    public function testDeleteGenre()
    {
        $uuid = (string) Uuid::uuid4();
        $mockEntity = Mockery::mock(
            Genre::class,
            ["Genre Name", $uuid, true, []]
        );

        $mockEntity->shouldReceive("id")->andReturn($uuid);

        $mockRepository = Mockery::mock(\stdClass::class, GenreRepositoryInterface::class);

        $mockRepository->shouldReceive('findById')
            ->once()
            ->with($uuid)
            ->andReturn($mockEntity);

        $mockRepository->shouldReceive('delete')
            ->once()
            ->with($uuid)
            ->andReturn(true);

        $inputDto = Mockery::mock(DeleteGenreInputDto::class, [$uuid]);

        $useCase = new DeleteGenreUseCase($mockRepository);
        $response = $useCase->execute($inputDto);

        $this->assertInstanceOf(DeleteGenreOutputDto::class, $response);
        $this->assertTrue($response->success);
    }

    public function testDeleteGenreShouldReturnFalse()
    {
        $uuid = (string) Uuid::uuid4();
        $mockEntity = Mockery::mock(
            Genre::class,
            ["Genre Name", $uuid, true, []]
        );

        $mockEntity->shouldReceive("id")->andReturn($uuid);

        $mockRepository = Mockery::mock(\stdClass::class, GenreRepositoryInterface::class);

        $mockRepository->shouldReceive('findById')
            ->once()
            ->with($uuid)
            ->andReturn($mockEntity);

        $mockRepository->shouldReceive('delete')
            ->once()
            ->with($uuid)
            ->andReturn(false);

        $inputDto = Mockery::mock(DeleteGenreInputDto::class, [$uuid]);

        $useCase = new DeleteGenreUseCase($mockRepository);
        $response = $useCase->execute($inputDto);

        $this->assertInstanceOf(DeleteGenreOutputDto::class, $response);
        $this->assertFalse($response->success);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
