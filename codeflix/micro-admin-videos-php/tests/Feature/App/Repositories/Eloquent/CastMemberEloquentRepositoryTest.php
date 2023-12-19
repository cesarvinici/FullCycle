<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\CastMember as CastMemberModel;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\Domain\Entity\CastMember as CastMemberEntity;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\FlareClient\Http\Exceptions\NotFound;
use Tests\TestCase;

class CastMemberEloquentRepositoryTest extends TestCase
{
    private CastMemberRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CastMemberEloquentRepository(new CastMemberModel());
    }

    public function testUsingCorrectInterface()
    {
        $this->assertInstanceOf(CastMemberRepositoryInterface::class, $this->repository);
    }

    public function testInsert()
    {
        $castMember = new CastMemberEntity(
            name: "test",
            type: CastMemberType::DIRECTOR
        );

        $response = $this->repository->insert($castMember);

       $this->assertDatabaseHas("cast_members", [
            "id" => $response->id(),
        ]);

       $this->assertSame($castMember->name, $response->name);
       $this->assertSame($castMember->type, $response->type);
       $this->assertIsString($response->id());
       $this->assertNotNull($response->createdAt());
       $this->assertNotNull($response->updatedAt());
    }

    public function testFindById()
    {
        $castMember = CastMemberModel::factory()->create();

        $response = $this->repository->findById($castMember->id);

        $this->assertSame($castMember->id, $response->id());
        $this->assertSame($castMember->name, $response->name);
        $this->assertSame(CastMemberType::from($castMember->type), $response->type);
    }

    public function testFindByIdWIthInvalidId()
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Cast Member not found");
        $this->repository->findById("invalid-id");
    }

    public function testFindAll()
    {
        CastMemberModel::factory()->count(10)->create();

        $response = $this->repository->findAll();

        $this->assertCount(10, $response);
        $this->assertContainsOnlyInstancesOf(CastMemberEntity::class, $response);
    }

    public function testFindAllByName()
    {
        CastMemberModel::factory()->count(10)->create();
        CastMemberModel::factory()->create(
            ['name' => "John Doe"]
        );

        $response = $this->repository->findAll($filter = "John Doe");

        $this->assertCount(1, $response);
        $this->assertContainsOnlyInstancesOf(CastMemberEntity::class, $response);
    }

    public function testFindAllEmpty()
    {
        $response = $this->repository->findAll();
        $this->assertCount(0, $response);
    }

    public function testPaginate()
    {
        CastMemberModel::factory()->count(45)->create();

        $response = $this->repository->paginate();

        $this->assertCount(15, $response->items());
        $this->assertEquals(1, $response->currentPage());
        $this->assertEquals(3, $response->lastPage());
        $this->assertEquals(45, $response->total());
    }

    public function testPaginate25PerPage()
    {
        CastMemberModel::factory()->count(45)->create();

        $response = $this->repository->paginate(perPage: 25);

        $this->assertCount(25, $response->items());
        $this->assertEquals(2, $response->lastPage());
        $this->assertEquals(45, $response->total());
    }

    public function testPaginatePage3()
    {
        CastMemberModel::factory()->count(45)->create();
        $response = $this->repository->paginate(page: 3);

        $this->assertCount(15, $response->items());
        $this->assertEquals(3, $response->currentPage());
        $this->assertEquals(3, $response->lastPage());
    }

    public function testPaginateFilterByName()
    {
        CastMemberModel::factory(45)->create();
        CastMemberModel::factory()->create(["name" => "test"]);

        $response = $this->repository->paginate(
            filter: "test"
        );

        $this->assertCount(1, $response->items());
        $this->assertEquals(1, $response->currentPage());
        $this->assertEquals(1, $response->lastPage());
        $this->assertEquals(1, $response->total());
    }

    public function testUpdate()
    {
        $castMember = CastMemberModel::factory()->create();

        $entity = new CastMemberEntity(
            id: $castMember->id,
            name: "test",
            type: CastMemberType::ACTOR
        );

        $response = $this->repository->update($entity);

        $this->assertSame($entity->id(), $response->id());
        $this->assertSame($entity->name, $response->name);
        $this->assertSame($entity->type, $response->type);
    }

    public function testUpdateInvalidId()
    {
        $entity = new CastMemberEntity(
            id: "invalid-id",
            name: "test",
            type: CastMemberType::ACTOR
        );

        $this->expectException(NotFoundException::class);

        $this->repository->update($entity);
    }

    public function testDelete()
    {
        $castMember = CastMemberModel::factory()->create();
        $this->repository->delete($castMember->id);
        $this->assertSoftDeleted("cast_members", [ "id" => $castMember->id]);
    }

    public function testDeleteInvalidId()
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Cast Member not found");
        $this->repository->delete("invalid-id");
    }

}
