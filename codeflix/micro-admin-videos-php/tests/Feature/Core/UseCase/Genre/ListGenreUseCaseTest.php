<?php

namespace Tests\Feature\Core\UseCase\Genre;



use App\Models\Genre as ModelGenre;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\Genre\DTO\ListGenre\ListGenreInputDto;
use Core\UseCase\Genre\DTO\ListGenre\ListGenreOutputDto;
use Core\UseCase\Genre\ListGenreUseCase;
use Tests\TestCase;

class ListGenreUseCaseTest extends TestCase
{
    private ListGenreUseCase $useCase;
    private GenreRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new GenreEloquentRepository(new ModelGenre());
        $this->useCase = new ListGenreUseCase($this->repository);
    }

    public function testExecute()
    {
        $model = ModelGenre::factory()->create();

        $input = new ListGenreInputDto($model->id);

        $response = $this->useCase->execute($input);

        $this->assertInstanceOf(ListGenreOutputDto::class, $response);
        $this->assertEquals($model->id, $response->id);
        $this->assertEquals($model->name, $response->name);
        $this->assertEquals($model->is_active, $response->is_active);
        $this->assertEquals(
            $model->categories()->pluck('id')->toArray(),
            $response->categoriesId
        );
    }

    public function testExecuteGenreNotFound()
    {
        $input = new ListGenreInputDto('invalid-id');
        $this->expectException(NotFoundException::class);
        $this->useCase->execute($input);
    }
}
