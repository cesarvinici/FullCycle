<?php

namespace Tests\Feature\Core\UseCase\Genre;



use App\Models\Genre as ModelGenre;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\Genre\DTO\ListGenres\ListGenresInputDto;
use Core\UseCase\Genre\DTO\ListGenres\ListGenresOutputDto;
use Core\UseCase\Genre\ListGenresUseCase;
use Core\UseCase\Genre\ListGenreUseCase;
use Tests\TestCase;

class ListGenresUseCaseTest extends TestCase
{
    private ListGenresUseCase $useCase;
    private GenreRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new GenreEloquentRepository(new ModelGenre());
        $this->useCase = new ListGenresUseCase($this->repository);
    }

    public function testExecute()
    {
        $model = ModelGenre::factory(45)->create();

        $input = new ListGenresInputDto(null);

        $response = $this->useCase->execute($input);

        $this->assertInstanceOf(ListGenresOutputDto::class, $response);
        $this->assertEquals($model->count(), $response->total);
        $this->assertCount(15, $response->items);
        $this->assertEquals(1, $response->currentPage);
        $this->assertEquals(1, $response->firstPage);
        $this->assertEquals(3, $response->lastPage);
        $this->assertEquals(15, $response->perPage);
    }

    public function testExecuteEmpty()
    {

        $input = new ListGenresInputDto(null);

        $response = $this->useCase->execute($input);

        $this->assertInstanceOf(ListGenresOutputDto::class, $response);
        $this->assertEquals(0, $response->total);
        $this->assertCount(0, $response->items);
        $this->assertEquals(1, $response->currentPage);
        $this->assertEquals(1, $response->firstPage);
        $this->assertEquals(1, $response->lastPage);
        $this->assertEquals(15, $response->perPage);
    }

    public function testExecuteWithFilter()
    {
        $model = ModelGenre::factory(45)->create();
        ModelGenre::factory()->create(['name' => 'test']);

        $input = new ListGenresInputDto('test');

        $response = $this->useCase->execute($input);

        $this->assertInstanceOf(ListGenresOutputDto::class, $response);
        $this->assertEquals(1, $response->total);
        $this->assertCount(1, $response->items);
        $this->assertEquals(1, $response->currentPage);
        $this->assertEquals(1, $response->firstPage);
        $this->assertEquals(1, $response->lastPage);
        $this->assertEquals(15, $response->perPage);
    }

    public function testExecuteFilterNameThatDontExist()
    {
        $model = ModelGenre::factory(45)->create();
        $input = new ListGenresInputDto('test');

        $response = $this->useCase->execute($input);

        $this->assertInstanceOf(ListGenresOutputDto::class, $response);
        $this->assertEquals(0, $response->total);
        $this->assertCount(0, $response->items);
        $this->assertEquals(1, $response->currentPage);
        $this->assertEquals(1, $response->firstPage);
        $this->assertEquals(1, $response->lastPage);
        $this->assertEquals(15, $response->perPage);
    }
}
