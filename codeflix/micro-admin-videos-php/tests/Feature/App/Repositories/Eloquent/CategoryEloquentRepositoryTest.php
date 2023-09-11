<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Domain\Entity\Category as EntityCategory;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Tests\TestCase;

class CategoryEloquentRepositoryTest extends TestCase
{
    private CategoryRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CategoryEloquentRepository(new Model());
    }

    public function testUsingCorrectInterface()
    {
        $this->assertInstanceOf(CategoryRepositoryInterface::class, $this->repository);
    }

    public function testInsert()
    {
        $entityCategory = new EntityCategory(
            name: "Teste"
        );

        $response = $this->repository->insert($entityCategory);

        $this->assertInstanceOf(EntityCategory::class, $response);

        $this->assertDatabaseHas(
            'categories',
            [
                'name' => $entityCategory->name
            ]
        );
    }
}
