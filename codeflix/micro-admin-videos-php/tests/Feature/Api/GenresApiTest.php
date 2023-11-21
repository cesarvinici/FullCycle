<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Genre;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class GenresApiTest extends TestCase
{
    private $endpoint = "/api/genres";

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testIndexEmpty()
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

    public function testIndex()
    {
        Genre::factory()->count(30)->create();
        $this->getJson($this->endpoint)
            ->assertOk()
            ->assertJson([
                "data" => [],
                "meta" => [
                    "total" => 30,
                    "currentPage" => 1,
                    "firstPage" => 1,
                    "lastPage" => 2,
                    "perPage" => 15,
                    "to" => 1,
                    "from" => 15,
                ]
            ]);
    }

    public function testStore()
    {
        $categoriesId = Category::factory()->count(3)->create()->pluck('id')->toArray();
        $genre = [
            "name" => "test",
            "is_active" => true,
            "categories_id" => $categoriesId
        ];

        $this->postJson($this->endpoint, $genre)
            ->assertCreated()
            ->assertJson(fn (AssertableJson $json) =>
                $json->where("name", $genre["name"])
                    ->where("is_active", $genre["is_active"])
                    ->has("id")
                    ->has("created_at")
                    ->has("updated_at")
            );
    }

    public function testStoreWithoutCategories()
    {
        $genre = [
            "name" => "test",
            "is_active" => true,
            "categories_id" => []
        ];

        $this->postJson($this->endpoint, $genre)
            ->assertUnprocessable()
            ->assertJson(fn (AssertableJson $json) =>
            $json->where("message", "The categories id field is required.")
                ->has("errors", fn (AssertableJson $json) =>
                    $json->where("categories_id", ["The categories id field is required."])
                )
            );
    }

    public function testStoreWithoutName()
    {
        $categoriesId = Category::factory()->count(3)->create()->pluck('id')->toArray();
        $genre = [
            "name" => "",
            "is_active" => true,
            "categories_id" => $categoriesId
        ];

        $this->postJson($this->endpoint, $genre)
            ->assertUnprocessable()
            ->assertJson(fn (AssertableJson $json) =>
            $json->where("message", "The name field is required.")
                ->has("errors", fn (AssertableJson $json) =>
                    $json->where("name", ["The name field is required."])
                )
            );
    }

    public function testShow()
    {
        $genre = Genre::factory()->create();

        $this->getJson("$this->endpoint/$genre->id")
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json->where("name", $genre->name)
                    ->where("is_active", $genre->is_active)
                    ->has("id")
                    ->has("created_at")
                    ->has("updated_at")
            );
    }

    public function testShowNotFound()
    {
        $this->getJson("$this->endpoint/1")
            ->assertNotFound();
    }

    public function testUpdate()
    {
        $categoriesId = Category::factory()->count(3)->create()->pluck('id')->toArray();
        $genre = Genre::factory()->create();
        $editedGenre  = [
            "id" => $genre->id,
            "name" => "test",
            "is_active" => true,
            "categories_id" => $categoriesId
        ];

        $this->putJson("$this->endpoint/$genre->id", $editedGenre)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json->where("name", $editedGenre["name"])
                    ->where("is_active", $editedGenre["is_active"])
                    ->has("id")
                    ->has("created_at")
                    ->has("updated_at")
            );
    }

    public function testUpdateWithInvalidCategory()
    {
        $categoriesId = Category::factory()->count(3)->create()->pluck('id')->toArray();
        $genre = Genre::factory()->create();
        $editedGenre  = [
            "id" => $genre->id,
            "name" => "test",
            "is_active" => true,
            "categories_id" => [uniqid()]
        ];

        $this->putJson("$this->endpoint/$genre->id", $editedGenre)
            ->assertUnprocessable()
            ->assertJson(fn (AssertableJson $json) =>
                $json->where("message", "The selected categories id is invalid.")
                    ->has("errors", fn (AssertableJson $json) =>
                        $json->where("categories_id", ["The selected categories id is invalid."])
                    )
            );
    }

    public function testDestroy()
    {
        $genre = Genre::factory()->create();

        $this->deleteJson("$this->endpoint/$genre->id")
            ->assertNoContent();
    }

    public function testDestroyNotFound()
    {
        $this->deleteJson("$this->endpoint/1")
            ->assertNotFound();
    }
}
