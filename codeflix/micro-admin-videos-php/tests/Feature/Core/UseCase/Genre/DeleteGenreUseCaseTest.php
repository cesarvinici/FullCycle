<?php

namespace Tests\Feature\Core\UseCase\Genre;




use App\Models\Genre;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\Genre\DeleteGenreUseCase;
use Core\UseCase\Genre\DTO\DeleteGenreInputDto;
use Core\UseCase\Genre\DTO\Update\DeleteGenreOutputDto;
use Tests\TestCase;

class DeleteGenreUseCaseTest extends TestCase
{
    private DeleteGenreUseCase $useCase;
    private GenreRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new GenreEloquentRepository(new Genre());
        $this->useCase = new DeleteGenreUseCase($this->repository);
    }

    public function testExecute()
    {
        $model = Genre::factory()->create();

        $input = new DeleteGenreInputDto($model->id);

        $response = $this->useCase->execute($input);

        $this->assertInstanceOf(DeleteGenreOutputDto::class, $response);
        $this->assertTrue($response->success);

        $this->assertSoftDeleted($model);
    }
}
