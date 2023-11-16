<?php

namespace Tests\Feature\Core\UseCase\Genre;

use App\Models\Category as ModelCategory;
use App\Models\Genre as ModelGenre;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use App\Repositories\Eloquent\GenreEloquentRepository;
use App\Repositories\Transaction\DBTransaction;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\Genre\CreateGenreUseCase;
use Core\UseCase\Genre\DTO\Create\CreateGenreInputDto;
use Core\UseCase\Genre\DTO\Create\CreateGenreOutputDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateGenreUseCaseTest extends TestCase
{

    private CreateGenreUseCase $useCase;
    private GenreRepositoryInterface $repository;
    private DBTransaction $transaction;
    private CategoryRepositoryInterface $categoryRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new GenreEloquentRepository(new ModelGenre());
        $this->transaction = new DBTransaction();
        $this->categoryRepository = new CategoryEloquentRepository(new ModelCategory());
        $this->useCase = new CreateGenreUseCase(
            $this->repository,
            $this->transaction,
            $this->categoryRepository
        );
    }

    public function testExecute()
    {
        $category = ModelCategory::factory()->create()->id;
        $input = new CreateGenreInputDto(
            name: 'Genre 1',
            is_active: true,
            categoriesId: [$category]
        );

        $output = $this->useCase->execute($input);

        $this->assertInstanceOf(CreateGenreOutputDto::class, $output);
        $this->assertNotNull($output->id);
        $this->assertIsString($output->id);
        $this->assertEquals($input->name, $output->name);
        $this->assertEquals($input->is_active, $output->is_active);
    }

    public function testExecuteWithoutCategories()
    {
        $this->expectException(NotFoundException::class);

        $input = new CreateGenreInputDto(
            name: 'Genre 1',
            is_active: true,
            categoriesId: []
        );

        $output = $this->useCase->execute($input);
    }

    public function testExecuteWithWrongCategoriesId()
    {
        $this->expectException(NotFoundException::class);

        $input = new CreateGenreInputDto(
            name: 'Genre 1',
            is_active: true,
            categoriesId: [uniqid()]
        );

        $output = $this->useCase->execute($input);
    }
}
