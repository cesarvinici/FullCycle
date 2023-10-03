<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as ModelCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Domain\Entity\Category;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\Category\DTO\Delete\DeleteCategoryInputDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteCategoryUseCaseTest extends TestCase
{
    private DeleteCategoryUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $repository = new CategoryEloquentRepository(new ModelCategory());
        $this->useCase = new DeleteCategoryUseCase($repository);
    }

    public function testExecute()
    {
        $category = ModelCategory::factory()->create();
        $input = new DeleteCategoryInputDto($category->id);

        $output = $this->useCase->execute($input);

        $this->assertTrue($output->success);

        $this->assertSoftDeleted('categories', [
            'id' => $category->id,
        ]);
    }
}
