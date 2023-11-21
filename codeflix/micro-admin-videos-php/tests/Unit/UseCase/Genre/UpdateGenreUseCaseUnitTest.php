<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\Genre\CreateGenreUseCase;
use Core\UseCase\Genre\DTO\Create\CreateGenreInputDto;
use Core\UseCase\Genre\DTO\Create\CreateGenreOutputDto;
use Core\UseCase\Genre\DTO\Update\UpdateGenreInputDto;
use Core\UseCase\Genre\DTO\Update\UpdateGenreOutputDto;
use Core\UseCase\Genre\UpdateGenreUseCase;
use Core\UseCase\Interfaces\TransactionInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Mockery;

class UpdateGenreUseCaseUnitTest extends TestCase
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

    public function testUpdateGenre()
    {
        $mockEntity = Mockery::mock(
            Genre::class,
            ["Genre Name", $this->genreUuid, true, []]
        );

        $mockEntity->shouldReceive("update")->once();
        $mockEntity->shouldReceive("deactivate")->once();
        $mockEntity->shouldReceive("activate")->never();
        $mockEntity->shouldReceive("addCategory")->twice();

        $anotherCategoryUuid = (string) Uuid::uuid4();

        $mockEntityEdited = Mockery::mock(
            Genre::class,
            ["Genre Name Edited", $this->genreUuid, false, [$this->categoryUuid, $anotherCategoryUuid]]
        );

        $mockEntityEdited->shouldReceive("id")->andReturn($this->genreUuid);
        $mockEntityEdited->shouldReceive("createdAt")->andReturn(date("Y-m-d H:i:s"));
        $mockEntityEdited->shouldReceive("updatedAt")->andReturn("Genre Name Edited");

        $this->mockCategoryRepository->shouldReceive('getCategoriesIds')
            ->once()
            ->andReturn([$this->categoryUuid, $anotherCategoryUuid]);

        $this->mockGenreRepository->shouldReceive('findById')
            ->once()
            ->andReturn($mockEntity);

        $this->mockGenreRepository->shouldReceive('update')
            ->once()
            ->andReturn($mockEntityEdited);

        $mockInputDto = Mockery::mock(UpdateGenreInputDto::class, [
            $this->genreUuid,
            "Genre Name Edited",
            false,
            [$this->categoryUuid, $anotherCategoryUuid],
        ]);

        $mockOutputDto = Mockery::mock(UpdateGenreOutputDto::class, [
            $this->genreUuid,
            "Genre Name Edited",
            false,
            [$this->categoryUuid, $anotherCategoryUuid],
            date("Y-m-d H:i:s"),
            date("Y-m-d H:i:s")
        ]);

        $this->mockTransaction->shouldReceive('commit')->once()->andReturn(true);
        $this->mockTransaction->shouldReceive('rollback')->never();

        $useCase = new UpdateGenreUseCase($this->mockGenreRepository, $this->mockTransaction, $this->mockCategoryRepository);
        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(UpdateGenreOutputDto::class, $response);
        $this->assertEquals($mockOutputDto->id, $response->id);
        $this->assertEquals($mockOutputDto->name, $response->name);
        $this->assertEquals($mockOutputDto->is_active, $response->is_active);
        $this->assertEquals($mockOutputDto->categoriesId, $response->categoriesId);
        $this->assertEquals($mockOutputDto->created_at, $response->created_at);

    }

    public function testUpdateShouldRemoveOneCategory()
    {
        $mockEntity = Mockery::mock(
            Genre::class,
            ["Genre Name", $this->genreUuid, true, [$this->categoryUuid]]
        );

        $mockEntity->shouldReceive("update")->once();
        $mockEntity->shouldReceive("deactivate")->once();
        $mockEntity->shouldReceive("activate")->never();
        $mockEntity->shouldReceive("addCategory")->once();
        $mockEntity->shouldReceive("removeCategory")->once()->with($this->categoryUuid);

        $anotherCategoryUuid = (string) Uuid::uuid4();

        $mockEntityEdited = Mockery::mock(
            Genre::class,
            ["Genre Name Edited", $this->genreUuid, false, [$anotherCategoryUuid]]
        );

        $mockEntityEdited->shouldReceive("id")->andReturn($this->genreUuid);
        $mockEntityEdited->shouldReceive("name")->andReturn("Genre Name Edited");
        $mockEntityEdited->shouldReceive("isActive")->andReturn(false);
        $mockEntityEdited->shouldReceive("categoriesId")->andReturn([$anotherCategoryUuid]);
        $mockEntityEdited->shouldReceive("createdAt")->andReturn(date("Y-m-d H:i:s"));
        $mockEntityEdited->shouldReceive("updatedAt")->andReturn(date("Y-m-d H:i:s"));

        $this->mockCategoryRepository->shouldReceive('getCategoriesIds')
            ->once()
            ->andReturn([$this->categoryUuid, $anotherCategoryUuid]);

        $this->mockGenreRepository->shouldReceive('findById')
            ->once()
            ->andReturn($mockEntity);

        $this->mockGenreRepository->shouldReceive('update')
            ->once()
            ->andReturn($mockEntityEdited);

        $mockInputDto = Mockery::mock(UpdateGenreInputDto::class, [
            $this->genreUuid,
            "Genre Name Edited",
            false,
            [$anotherCategoryUuid],
        ]);

        $mockOutputDto = Mockery::mock(UpdateGenreOutputDto::class, [
            $this->genreUuid,
            "Genre Name Edited",
            false,
            [$anotherCategoryUuid],
            date("Y-m-d H:i:s"),
            date("Y-m-d H:i:s")
        ]);

        $this->mockTransaction->shouldReceive('commit')->once()->andReturn(true);
        $this->mockTransaction->shouldReceive('rollback')->never();

        $useCase = new UpdateGenreUseCase($this->mockGenreRepository, $this->mockTransaction, $this->mockCategoryRepository);
        $response = $useCase->execute($mockInputDto, );

        $this->assertInstanceOf(UpdateGenreOutputDto::class, $response);
        $this->assertEquals($mockOutputDto->id, $response->id);
        $this->assertEquals($mockOutputDto->name, $response->name);
        $this->assertEquals($mockOutputDto->is_active, $response->is_active);
        $this->assertEquals($mockOutputDto->categoriesId, $response->categoriesId);
        $this->assertEquals($mockOutputDto->created_at, $response->created_at);

    }

    public function testUpdateGenreInvalidCategory()
    {
        $mockEntity = Mockery::mock(
            Genre::class,
            ["Genre Name", $this->genreUuid, true, []]
        );

        $this->mockCategoryRepository->shouldReceive('getCategoriesIds')
            ->once()
            ->andReturn([]);

        $this->mockGenreRepository->shouldReceive('findById')
            ->once()
            ->andReturn($mockEntity);

        $this->mockGenreRepository->shouldReceive('update')
            ->never();

        $mockInputDto = Mockery::mock(UpdateGenreInputDto::class, [
            $this->genreUuid,
            "Genre Name Edited",
            false,
            [$this->categoryUuid],
        ]);

        $this->mockTransaction->shouldReceive('commit')->never();
        $this->mockTransaction->shouldReceive('rollback')->once()->andReturn(true);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Categories not found: {$this->categoryUuid}");

        $useCase = new UpdateGenreUseCase($this->mockGenreRepository, $this->mockTransaction, $this->mockCategoryRepository);
        $response = $useCase->execute($mockInputDto, );
    }
}
