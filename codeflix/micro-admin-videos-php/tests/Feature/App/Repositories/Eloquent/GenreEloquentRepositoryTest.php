<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Domain\Entity\Genre as GenreEntity;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Tests\TestCase;
use App\Models\Genre as ModelGenre;

class GenreEloquentRepositoryTest extends TestCase
{
    private GenreRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new GenreEloquentRepository(new ModelGenre());
    }

    public function testUsingCorrectInterface()
    {
        $this->assertInstanceOf(GenreRepositoryInterface::class, $this->repository);
    }

    public function testInsert()
    {
        $entity = new GenreEntity(
            name: "test",
        );

        $response = $this->repository->insert($entity);

        $this->assertInstanceOf(GenreEntity::class, $response);
        $this->assertEquals($entity->name, $response->name);
        $this->assertEquals($entity->isActive, $response->isActive);
        $this->assertIsString($response->id());
        $this->assertIsString($response->createdAt());

        $this->assertDatabaseHas("genres", [
            "id" => $response->id(),
            "name" => $response->name,
            "is_active" => $response->isActive,
            "created_at" => $response->createdAt(),
        ]);
    }

    public function testInsertWithCategories()
    {

        $categories = Category::factory(4)->create();

        $entity = new GenreEntity(
            name: "test"
        );

        $categories->each(function ($category) use ($entity) {
            $entity->addCategory($category->id);
        });

        $this->assertCount(4, $entity->categoriesId);

        $response = $this->repository->insert($entity);

        $categories->each(function ($category) use ($response) {
            $this->assertDatabaseHas("category_genre", [
                "category_id" => $category->id,
                "genre_id" => $response->id()
            ]);
        });
    }

    public function testFindByIdNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->findById("invalid-id");
    }

    public function testFindById()
    {
        $genre = ModelGenre::factory()->create();

        $response = $this->repository->findById($genre->id);

        $this->assertInstanceOf(GenreEntity::class, $response);
        $this->assertEquals($genre->id, $response->id());
        $this->assertEquals($genre->name, $response->name);
        $this->assertEquals($genre->is_active, $response->isActive);
    }

    public function testFindAll()
    {
        ModelGenre::factory(10)->create();

        $response = $this->repository->findAll();

        $this->assertIsArray($response);
        $this->assertCount(10, $response);
    }

    public function testFindAllEmpty()
    {
        $response = $this->repository->findAll();

        $this->assertIsArray($response);
        $this->assertCount(0, $response);
    }


    public function testFindAllWithFilter()
    {
        ModelGenre::factory(10)->create();
        ModelGenre::factory()->create(["name" => "test"]);

        $response = $this->repository->findAll("test");

        $this->assertIsArray($response);
        $this->assertCount(1, $response);
        $this->assertEquals("test", $response[0]->name);
    }

    public function testPagination()
    {
        ModelGenre::factory(45)->create();

        $response = $this->repository->paginate();

        $this->assertIsArray($response->items());
        $this->assertCount(15, $response->items());
        $this->assertEquals(1, $response->currentPage());
        $this->assertEquals(3, $response->lastPage());
        $this->assertEquals(45, $response->total());
    }

    public function testPagination25ItemsPerPage()
    {
        ModelGenre::factory(45)->create();

        $response = $this->repository->paginate(
            perPage: 25
        );

        $this->assertIsArray($response->items());
        $this->assertCount(25, $response->items());
        $this->assertEquals(1, $response->currentPage());
        $this->assertEquals(2, $response->lastPage());
        $this->assertEquals(45, $response->total());
    }

    public function testPaginationPage3()
    {
        ModelGenre::factory(45)->create();

        $response = $this->repository->paginate(
            page: 3
        );

        $this->assertIsArray($response->items());
        $this->assertCount(15, $response->items());
        $this->assertEquals(3, $response->currentPage());
        $this->assertEquals(3, $response->lastPage());
        $this->assertEquals(45, $response->total());
    }

    public function testPaginationFilter()
    {
        ModelGenre::factory(45)->create();
        ModelGenre::factory()->create(["name" => "test"]);

        $response = $this->repository->paginate(
            filter: "test"
        );

        $this->assertIsArray($response->items());
        $this->assertCount(1, $response->items());
        $this->assertEquals(1, $response->currentPage());
        $this->assertEquals(1, $response->lastPage());
        $this->assertEquals(1, $response->total());
    }

    public function testUpdate()
    {
        $model = ModelGenre::factory()->create();

        $entity = new GenreEntity(
            id: new Uuid($model->id),
            name: $model->name,
            isActive: $model->is_active,
            createdAt: $model->created_at
        );

        $entity->update("test2", true);
        $entity->deactivate();

        $response = $this->repository->update($entity);

        $this->assertInstanceOf(GenreEntity::class, $response);
        $this->assertEquals($entity->id(), $response->id());
        $this->assertEquals($entity->name, $response->name);
        $this->assertEquals($entity->isActive, $response->isActive);
        $this->assertEquals($entity->createdAt(), $response->createdAt());
    }

    public function testUpdateNotFound()
    {

        $this->expectException(NotFoundException::class);

        $model = ModelGenre::factory()->create();

        $entity = new GenreEntity(
            id: uniqid(),
            name: $model->name,
            isActive: $model->is_active,
            createdAt: $model->created_at
        );

        $entity->update("test2", true);
        $entity->deactivate();

        $response = $this->repository->update($entity);
    }

    public function testUpdateWithCategories()
    {
        $model = ModelGenre::factory()->create();

        $entity = new GenreEntity(
            id: new Uuid($model->id),
            name: $model->name,
            isActive: $model->is_active,
            createdAt: $model->created_at
        );

        $entity->addCategory(Category::factory()->create()->id);
        $response = $this->repository->update($entity);

        $this->assertInstanceOf(GenreEntity::class, $response);
        $this->assertDatabaseHas("category_genre", [
            "category_id" => $entity->categoriesId[0],
            "genre_id" => $entity->id()
        ]);
    }

    public function testDelete()
    {
        $model = ModelGenre::factory()->create();

        $this->repository->delete($model->id);

        $this->assertSoftDeleted("genres", [
            "id" => $model->id
        ]);
    }

    public function testDeleteNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->delete(uniqid());
    }
}
