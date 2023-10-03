<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as ModelCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\Category\DTO\ListCategories\ListCategoriesInputDto;
use Core\UseCase\Category\ListCategoriesUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListCategoriesUseCaseTest extends TestCase
{
    private ListCategoriesUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $repository = new CategoryEloquentRepository(new ModelCategory());
        $this->useCase = new ListCategoriesUseCase($repository);
    }

    public function testExecute()
    {
        ModelCategory::factory()->count(10)->create();

        $input = new ListCategoriesInputDto(
            filter: null,
            order: 'DESC',
            page: 1,
            perPage: 15,
        );

        $output = $this->useCase->execute($input);
        $this->assertEquals(10, $output->total);
    }

    public function testExecuteEmpty()
    {
        $input = new ListCategoriesInputDto(
            filter: null,
            order: 'DESC',
            page: 1,
            perPage: 15,
        );

        $output = $this->useCase->execute($input);
        $this->assertEquals(0, $output->total);
    }
}
