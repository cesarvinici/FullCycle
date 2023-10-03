<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category;
use App\Models\Category as ModelCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\DTO\ListCategory\ListCategoryInputDto;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\Category\ListCategoryUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListCategoryUseCaseTest extends TestCase
{
    private ListCategoryUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $repository = new CategoryEloquentRepository(new ModelCategory());
        $this->useCase = new ListCategoryUseCase($repository);
    }

    public function testExecute()
    {
        $category = Category::factory()->create();
        $input = new ListCategoryInputDto(
            id: $category->id
        );

        $response = $this->useCase->execute($input);

        $this->assertEquals($category->id, $response->id);
        $this->assertEquals($category->name, $response->name);
        $this->assertEquals($category->description, $response->description);
        $this->assertEquals($category->is_active, $response->is_active);
    }

}
