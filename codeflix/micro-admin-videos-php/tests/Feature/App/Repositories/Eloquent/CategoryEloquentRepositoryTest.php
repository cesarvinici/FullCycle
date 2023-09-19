<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category as ModelCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Domain\Entity\Category as EntityCategory;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoryEloquentRepositoryTest extends TestCase
{
    private CategoryRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CategoryEloquentRepository(new ModelCategory());
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

    public function testFindById()
    {
        $category = ModelCategory::factory()->create();
        $response = $this->repository->findById($category->id);

        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertEquals($category->id, $response->id());
        $this->assertEquals($category->name, $response->name);
        $this->assertEquals($category->description, $response->description);
        $this->assertEquals($category->is_active, $response->isActive);

    }

    public function testCategoryIdNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->findById("not-found");
    }

    public function testFindAll()
    {
        $categories = ModelCategory::factory()->count(10)->create();

        $response = $this->repository->findAll();

        $this->assertIsArray($response);
        $this->assertCount(10, $response);
    }

    public function testFindAllFilterByName()
    {
        ModelCategory::factory()->count(10)->create();
        ModelCategory::factory()->create(['name' => 'Filter-Test']);

        $response = $this->repository->findAll('Filter-Test');

        $this->assertIsArray($response);
        $this->assertCount(1, $response);
    }

    public function testPaginate()
    {
        $categories = ModelCategory::factory()->count(50)->create();

        $response = $this->repository->paginate();

        $this->assertInstanceOf(PaginationInterface::class, $response);

        $this->assertIsArray($response->items());
        $this->assertCount(15, $response->items());
        $this->assertEquals(1, $response->currentPage());
        $this->assertEquals(1, $response->firstPage());
        $this->assertEquals(4, $response->lastPage());
        $this->assertEquals(15, $response->perPage());
        $this->assertEquals(1, $response->to());
        $this->assertEquals(15, $response->from());

        $response = $this->repository->paginate("", "desc", 2, 10);
        $this->assertCount(10, $response->items());
        $this->assertEquals(2, $response->currentPage());
        $this->assertEquals(1, $response->firstPage());
        $this->assertEquals(5, $response->lastPage());
        $this->assertEquals(10, $response->perPage());
        $this->assertEquals(11, $response->to());
        $this->assertEquals(20, $response->from());
    }

    public function testPaginateEmptyData()
    {
        $response = $this->repository->paginate();

        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(0, $response->items());
        $this->assertEquals(1, $response->currentPage());
        $this->assertEquals(1, $response->firstPage());
        $this->assertEquals(1, $response->lastPage());
        $this->assertEquals(15, $response->perPage());
        $this->assertEquals(0, $response->to());
        $this->assertEquals(0, $response->from());
    }

    public function testUpdate()
    {
        $category = ModelCategory::factory()->create();

        $entityCategory = new EntityCategory(
            id: $category->id,
            name: "Edited name",
            description: "Edited description",
            isActive: false,
            createdAt: $category->created_at,
            updatedAt: $category->updated_at
        );

        $response = $this->repository->update($entityCategory);
        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertEquals($entityCategory->id(), $response->id());
        $this->assertEquals($entityCategory->name, $response->name);
        $this->assertEquals($entityCategory->description, $response->description);
        $this->assertFalse($response->isActive);
    }

    public function testUpdateWrongCategoryId()
    {
        $category = ModelCategory::factory()->create();

        $entityCategory = new EntityCategory(
            id: Str::uuid(),
            name: "Edited name",
            description: "Edited description",
            isActive: false,
            createdAt: $category->created_at,
            updatedAt: $category->updated_at
        );

        $this->expectException(NotFoundException::class);
        $this->repository->update($entityCategory);
    }

    public function testDeleteCategory()
    {
        $category = ModelCategory::factory()->create();

        $response = $this->repository->delete($category->id);

        $this->assertTrue($response);

        $this->assertSoftDeleted('categories', [
            'id' => $category->id
        ]);
    }

    public function testDeleteCategoryWrongId()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->delete(Str::uuid());
    }
}
