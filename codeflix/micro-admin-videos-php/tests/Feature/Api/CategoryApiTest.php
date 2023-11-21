<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    private $endpoint = "/api/categories";

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testListEmptyCategories()
    {
        $this->getJson($this->endpoint)
            ->assertOk()
            ->assertJson([
                "data" => [],
                "meta" => [
                    "total" => 0,
                    "currentPage" => 1,
                    "firstPage" => 1,
                    "lastPage" => 1,
                    "perPage" => 15,
                    "to" => 0,
                    "from" => 0,
                ]
            ]);
    }

    public function testListCategories()
    {
        Category::factory()->count(30)->create();

        $this->getJson($this->endpoint)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', 15)
                    ->has('meta', fn ($json) =>
                    $json->where('total', 30)
                        ->where('currentPage', 1)
                        ->where('firstPage', 1)
                        ->where('lastPage', 2)
                        ->where('perPage', 15)
                        ->where('to', 1)
                        ->where('from', 15)
                    ));
    }

    public function testListCategoriesPage2()
    {
        Category::factory()->count(20)->create();

        $this->getJson("$this->endpoint?page=2")
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 5)
                ->has('meta', fn ($json) =>
                $json->where('total', 20)
                    ->where('currentPage', 2)
                    ->where('firstPage', 1)
                    ->where('lastPage', 2)
                    ->where('perPage', 15)
                    ->where('to', 16)
                    ->where('from', 20)
                ));
    }

    public function testListCategoryNotFound()
    {
        $this->getJson("$this->endpoint/1")
            ->assertNotFound()
            ->assertJson([
                "message" => "Category not found"
            ]);
    }

    public function testListCategory()
    {
        $category = Category::factory()->create();

        $this->getJson("$this->endpoint/$category->id")
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('id', $category->id)
                    ->where('name', $category->name)
                    ->where('description', $category->description)
                    ->where('is_active', $category->is_active)
                    ->where('created_at', $category->created_at->toDatetimeString())
                    ->where('updated_at', $category->updated_at->toDatetimeString())
                );
    }

    public function testStoreEmptyData()
    {
        $this->postJson($this->endpoint, [])
            ->assertUnprocessable()
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('message', 'The name field is required.')
                    ->has('errors', fn ($json) =>
                        $json->where('name', ['The name field is required.'])
                    )
            );
    }

    public function testStoreData()
    {
        $this->post($this->endpoint, [
            "name" => "test",
            "description" => "test description"
        ])
            ->assertCreated()
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('name', 'test')
                    ->where('description', 'test description')
                    ->where('is_active', true)
                    ->has("id")
                    ->has('created_at')
                    ->has('updated_at')
            );
    }

    public function testStoreDataOnlyName()
    {
        $this->post($this->endpoint, ["name" => "test"])
            ->assertCreated()
            ->assertJson(fn (AssertableJson $json) =>
            $json->where('name', 'test')
                ->where('description', "")
                ->where('is_active', true)
                ->has("id")
                ->has('created_at')
                ->has('updated_at')
            );
    }

    public function testUpdateInvalidCategory()
    {
        $this->putJson("$this->endpoint/1", [
            "name" => "update"
        ])
            ->assertNotFound()
            ->assertJson([
                "message" => "Category not found"
            ]);
    }

    public function testUpdateInvalidData()
    {
        $this->putJson("$this->endpoint/1", [])
            ->assertUnprocessable()
            ->assertJson([
                "message" => "The name field is required."
            ]);
    }

    public function testUpdate()
    {

        $category = Category::factory()->create();

        $this->putJson("$this->endpoint/$category->id", [
            "name" => "update",
            "description" => "update description",
        ])
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json
                    ->where("id" , $category->id)
                    ->where('name', 'update')
                    ->where('description', 'update description')
                    ->where('is_active', true)
                    ->has('created_at')
                    ->has('updated_at')
            );
    }

    public function testDeleteInvalidCategory()
    {
        $this->deleteJson("$this->endpoint/1")
            ->assertNotFound()
            ->assertJson([
                "message" => "Category not found"
            ]);
    }

    public function testDelete()
    {
        $category = Category::factory()->create();

        $this->deleteJson("$this->endpoint/$category->id")
            ->assertNoContent();

        $this->assertSoftDeleted($category);
    }
}
