<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as ModelCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\DTO\Update\UpdateCategoryInputDto;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateCategoryUseCaseTest extends TestCase
{
    private UpdateCategoryUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $repository = new CategoryEloquentRepository(new ModelCategory());
        $this->useCase = new UpdateCategoryUseCase($repository);
    }

    public function testExecute()
    {
        $category = ModelCategory::factory()->create();

        $input = new UpdateCategoryInputDto(
            id: $category->id,
            name: 'Category 1',
            description: 'Description 1',
            is_active: true
        );

        $output = $this->useCase->execute($input);

        $this->assertEquals($input->id, $output->id);
        $this->assertEquals($input->name, $output->name);
        $this->assertEquals($input->description, $output->description);
        $this->assertEquals($input->is_active, $output->is_active);

        $this->assertDatabaseHas('categories', [
            'id' => $input->id,
            'name' => $input->name,
            'description' => $input->description,
            'is_active' => $input->is_active,
        ]);
    }
}
