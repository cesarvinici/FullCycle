<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\Genre\CreateGenreUseCase;
use Core\UseCase\Genre\DTO\Create\CreateGenreInputDto;
use Core\UseCase\Genre\DTO\Create\CreateGenreOutputDto;
use Core\UseCase\Interfaces\TransactionInterface;
use PHPUnit\Framework\TestCase;
use Mockery;
use Ramsey\Uuid\Uuid;


class CreateGenreUseCaseUnitTest extends TestCase
{

    private CategoryRepositoryInterface $mockCategoryRepository;
    private TransactionInterface $mockTransaction;
    private GenreRepositoryInterface $mockGenreRepository;
    private string $genreUuid;
    private string $categoryUuid;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockCategoryRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $this->mockGenreRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);

        $this->genreUuid = (string) Uuid::uuid4();
        $this->categoryUuid = (string) Uuid::uuid4();
    }


    public function testCreateGenre()
    {

        $mockEntity = Mockery::mock(
            Genre::class,
            ["Genre Name", $this->genreUuid, true, [$this->categoryUuid]]
        );

        $mockEntity->shouldReceive("id")->andReturn($this->genreUuid);
        $mockEntity->shouldReceive("createdAt")->andReturn(date("Y-m-d H:i:s"));

        $this->mockCategoryRepository->shouldReceive('getCategoriesIds')
            ->once()
            ->andReturn([$this->categoryUuid]);

        $this->mockGenreRepository->shouldReceive('insert')
            ->once()
            ->andReturn($mockEntity);

        $mockInputDto = Mockery::mock(CreateGenreInputDto::class, [
            "Genre Name",
            true,
            [$this->categoryUuid],
        ]);

        $mockOutputDto = Mockery::mock(CreateGenreOutputDto::class, [
            $this->genreUuid,
            "Genre Name",
            true,
            [$this->categoryUuid],
            date("Y-m-d H:i:s")
        ]);

        $this->mockTransaction->shouldReceive('commit')->once()->andReturn(true);
        $this->mockTransaction->shouldReceive('rollback')->never();

        $useCase = new CreateGenreUseCase($this->mockGenreRepository, $this->mockTransaction, $this->mockCategoryRepository);
        $response = $useCase->execute($mockInputDto, );

        $this->assertInstanceOf(CreateGenreOutputDto::class, $response);
        $this->assertEquals($mockOutputDto->id, $response->id);
        $this->assertEquals($mockOutputDto->name, $response->name);
        $this->assertEquals($mockOutputDto->is_active, $response->is_active);
        $this->assertEquals($mockOutputDto->categoriesId, $response->categoriesId);
        $this->assertEquals($mockOutputDto->created_at, $response->created_at);

    }

    public function testCreateGenreWithInvalidCategory()
    {
        $this->mockCategoryRepository->shouldReceive('getCategoriesIds')
            ->once()
            ->andReturn([]);

        $this->mockGenreRepository->shouldReceive('insert')
            ->never();

        $mockInputDto = Mockery::mock(CreateGenreInputDto::class, [
            "Genre Name",
            true,
            [$this->categoryUuid],
        ]);

        $this->mockTransaction->shouldReceive('commit')->never();
        $this->mockTransaction->shouldReceive('rollback')->once()->andReturn(true);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Categories not found: {$this->categoryUuid}");

        $useCase = new CreateGenreUseCase($this->mockGenreRepository, $this->mockTransaction, $this->mockCategoryRepository);
        $response = $useCase->execute($mockInputDto, );
    }

    public function testCreateGenreWithNoCategory()
    {
        $this->mockCategoryRepository->shouldReceive('getCategoriesIds')
            ->never();

        $this->mockGenreRepository->shouldReceive('insert')
            ->never();

        $mockInputDto = Mockery::mock(CreateGenreInputDto::class, [
            "Genre Name",
            true,
            [],
        ]);

        $this->mockTransaction->shouldReceive('commit')->never();
        $this->mockTransaction->shouldReceive('rollback')->once()->andReturn(true);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("You must specify a category");

        $useCase = new CreateGenreUseCase($this->mockGenreRepository, $this->mockTransaction, $this->mockCategoryRepository);
        $response = $useCase->execute($mockInputDto, );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
