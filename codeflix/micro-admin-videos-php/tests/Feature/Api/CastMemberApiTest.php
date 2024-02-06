<?php

namespace Tests\Feature\Api;

use App\Models\CastMember;
use Core\Domain\Enum\CastMemberType;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CastMemberApiTest extends TestCase
{
    private $endpoint = "/api/cast-members";

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testEmptyCastMembers()
    {
        $this->getJson($this->endpoint)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', 0)
                    ->has('meta', fn ($json) =>
                        $json->where('total', 0)
                            ->where('currentPage', 1)
                            ->where('firstPage', 1)
                            ->where('lastPage', 1)
                            ->where('perPage', 15)
                            ->where('to', 0)
                            ->where('from', 0)
                    ));
    }

    public function testListCastMembers()
    {
        CastMember::factory()->count(30)->create();

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

    public function testListCastMembersPage2()
    {
        CastMember::factory()->count(20)->create();

        $this->getJson($this->endpoint . "?page=2")
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

    public function testListCastMemberNotFound()
    {
        $this->getJson("$this->endpoint/999")
            ->assertNotFound();
    }

    public function testListCastMember()
    {
        $castMember = CastMember::factory()->create();

        $this->getJson("$this->endpoint/$castMember->id")
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                    $json->where('id', $castMember->id)
                        ->where('name', $castMember->name)
                        ->where('type', $castMember->type)
                        ->where('created_at', $castMember->created_at->toDateTimeString())
                        ->where('updated_at', $castMember->updated_at->toDateTimeString())
                    );
    }

    public function testStore()
    {
        $this->postJson($this->endpoint, [
            'name' => 'test',
            'type' => CastMemberType::DIRECTOR->value
        ])
            ->assertCreated()
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('id')
                    ->where('name', 'test')
                    ->where('type', CastMemberType::DIRECTOR->value)
                    ->has('created_at')
                    ->has('updated_at')
            );
    }

    public function testStoreEmptyData()
    {
        $this->postJson($this->endpoint, [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'type']);
    }

    public function testStoreOnlyName()
    {
        $this->postJson($this->endpoint, [
            'name' => 'test'
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['type']);

    }

    public function testStoreOnlyType()
    {
        $this->postJson($this->endpoint, [
            'type' => CastMemberType::DIRECTOR->value
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function testUpdate()
    {
        $castMember = CastMember::factory()->create();

        $this->putJson("$this->endpoint/$castMember->id", [
            'name' => 'test',
            'type' => CastMemberType::DIRECTOR->value
        ])
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('id', $castMember->id)
                    ->where('name', 'test')
                    ->where('type', CastMemberType::DIRECTOR->value)
                    ->has('created_at')
                    ->has('updated_at')
            );
    }

    public function testUpdateEmptyData()
    {
        $castMember = CastMember::factory()->create();

        $this->putJson("$this->endpoint/$castMember->id", [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'type']);
    }

    public function testUpdateOnlyName()
    {
        $castMember = CastMember::factory()->create();

        $this->putJson("$this->endpoint/$castMember->id", [
            'name' => 'test'
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['type']);
    }

    public function testUpdateOnlyType()
    {
        $castMember = CastMember::factory()->create();

        $this->putJson("$this->endpoint/$castMember->id", [
            'type' => CastMemberType::DIRECTOR->value
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function testDelete()
    {
        $castMember = CastMember::factory()->create();
        $this->deleteJson("$this->endpoint/$castMember->id")
            ->assertNoContent();

        $this->assertSoftDeleted($castMember);
    }

    public function testDeleteNotFound()
    {
        $this->deleteJson("$this->endpoint/999")
            ->assertNotFound();
    }
}
