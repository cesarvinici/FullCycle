<?php

namespace Tests\Feature\Core\UseCase\Genre;


use App\Models\Category;
use App\Models\Genre;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use App\Repositories\Eloquent\GenreEloquentRepository;
use App\Repositories\Transaction\DBTransaction;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\Genre\DTO\Update\UpdateGenreInputDto;
use Core\UseCase\Genre\DTO\Update\UpdateGenreOutputDto;
use Core\UseCase\Genre\UpdateGenreUseCase;
use Tests\TestCase;

class UpdateGenreUseCaseTest extends TestCase
{

    private UpdateGenreUseCase $useCase;
    private GenreRepositoryInterface $repository;
    private DBTransaction $transaction;
    private CategoryRepositoryInterface $categoryRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new GenreEloquentRepository(new Genre());
        $this->transaction = new DBTransaction();
        $this->categoryRepository = new CategoryEloquentRepository(new Category());
        $this->useCase = new UpdateGenreUseCase(
            $this->repository,
            $this->transaction,
            $this->categoryRepository
        );
    }

    public function testExecute()
    {
        $category = Category::factory()->create()->id;

        $genre = Genre::factory()->create();

        $input = new UpdateGenreInputDto(
            id: $genre->id,
            name: 'Genre 1',
            is_active: true,
            categoriesId: [$category]
        );

        $output = $this->useCase->execute($input);

        $this->assertInstanceOf(UpdateGenreOutputDto::class, $output);
        $this->assertEquals($input->id, $output->id);
        $this->assertEquals($input->name, $output->name);
        $this->assertEquals($input->is_active, $output->is_active);
        $this->assertEquals($input->categoriesId, $output->categoriesId);
    }

    public function testExecuteRemovingOneCategory()
    {
        $categories = Category::factory(2)
            ->create()
            ->pluck('id')
            ->toArray();

        $genre = Genre::factory()->create();
        $genre->categories()->sync($categories);

        $input = new UpdateGenreInputDto(
            id: $genre->id,
            name: $genre->name,
            is_active: true,
            categoriesId: [$categories[0]]
        );

        $output = $this->useCase->execute($input);

        $this->assertInstanceOf(UpdateGenreOutputDto::class, $output);
        $this->assertEquals($input->id, $output->id);
        $this->assertEquals($input->name, $output->name);
        $this->assertEquals($input->is_active, $output->is_active);
        $this->assertEquals($input->categoriesId, $output->categoriesId);
    }

    public function testExecuteReplacingAllCategories()
    {
        $categories = Category::factory(2)
            ->create()
            ->pluck('id')
            ->toArray();

        $genre = Genre::factory()->create();
        $genre->categories()->sync($categories);

        $newCategories = Category::factory(2)
            ->create()
            ->pluck('id')
            ->toArray();


        $input = new UpdateGenreInputDto(
            id: $genre->id,
            name: $genre->name,
            is_active: true,
            categoriesId: $newCategories
        );

        $output = $this->useCase->execute($input);

        $this->assertInstanceOf(UpdateGenreOutputDto::class, $output);
        $this->assertEquals($input->id, $output->id);
        $this->assertEquals($input->name, $output->name);
        $this->assertEquals($input->is_active, $output->is_active);
        $this->assertEquals($input->categoriesId, $output->categoriesId);
    }

    public function testExecuteWithoutCategories()
    {
        $this->expectException(NotFoundException::class);

        $genre = Genre::factory()->create();

        $input = new UpdateGenreInputDto(
            id: $genre->id,
            name: 'Genre 1',
            is_active: true,
            categoriesId: []
        );

        $this->useCase->execute($input);
    }

    public function testExecuteWithWrongCategoriesId()
    {
        $this->expectException(NotFoundException::class);

        $genre = Genre::factory()->create();

        $input = new UpdateGenreInputDto(
            id: $genre->id,
            name: 'Genre 1',
            is_active: true,
            categoriesId: [uniqid()]
        );

        $this->useCase->execute($input);
    }
}
