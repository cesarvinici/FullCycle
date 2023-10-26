<?php

namespace Tests\Feature\App\Http\Controllers\Api;

use App\Http\Controllers\Api\CategoryController;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{

    private CategoryRepositoryInterface $repository;
    private CategoryController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $model = new Category();
        $this->repository = new CategoryEloquentRepository($model);
        $this->controller = new CategoryController();
    }

    public function testIndex()
    {
        $useCase = new ListCategoriesUseCase($this->repository);
        $response = $this->controller->index(new Request(), $useCase);

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertEmpty($response->resource);
        $this->assertArrayHasKey("meta", $response->additional);
    }

    public function testStore()
    {
        $useCase = new CreateCategoryUseCase($this->repository);
        $request = new StoreCategoryRequest();
        $request->headers->set("content-type", "application/json");
        $request->setJson(new ParameterBag([
            "name" => "test",
            "description" => "test description"
        ]));

        $response = $this->controller->store($request, $useCase);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
    }

    public function testShow()
    {
        $category = Category::factory()->create();

        $useCase = new ListCategoryUseCase($this->repository);
        $response = $this->controller->show(
            useCase: $useCase,
            id: $category->id
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $category = Category::factory()->create();

        $useCase = new UpdateCategoryUseCase($this->repository);
        $request = new UpdateCategoryRequest();
        $request->headers->set("content-type", "application/json");
        $request->setJson(new ParameterBag([
            "name" => "Updated",
        ]));

        $response = $this->controller->update(
            request: $request,
            id: $category->id,
            useCase: $useCase
        );

        $category->refresh();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals("Updated", $category->name);
    }

    public function testDestroy()
    {
        $category = Category::factory()->create();

        $useCase = new DeleteCategoryUseCase($this->repository);

        $response = $this->controller->destroy(
            id: $category->id,
            useCase: $useCase
        );

        $category->refresh();
        $this->assertSoftDeleted($category);
    }
}
