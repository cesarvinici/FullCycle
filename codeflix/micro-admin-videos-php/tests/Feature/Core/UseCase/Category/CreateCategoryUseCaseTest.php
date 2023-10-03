<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as ModelCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\Category\DTO\Insert\InsertCategoryInputDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateCategoryUseCaseTest extends TestCase
{
    private CreateCategoryUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $repository = new CategoryEloquentRepository(new ModelCategory());
        $this->useCase = new CreateCategoryUseCase($repository);
    }

    public function testExecute()
    {
            $input = new InsertCategoryInputDto(
            name: 'Category 1',
            description: 'Description 1',
            isActive: true
        );

        $output = $this->useCase->execute($input);

        $this->assertNotNull($output->id);
        $this->assertEquals($input->name, $output->name);
        $this->assertEquals($input->description, $output->description);
        $this->assertEquals($input->isActive, $output->is_active);

        $this->assertDatabaseHas('categories', [
            'id' => $output->id,
            'name' => $output->name,
            'description' => $output->description,
            'is_active' => $output->is_active,
        ]);
    }
}
