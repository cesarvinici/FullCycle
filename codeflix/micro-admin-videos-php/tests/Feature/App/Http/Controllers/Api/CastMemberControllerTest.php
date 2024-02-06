<?php

namespace Tests\Feature\App\Http\Controllers\Api;

use App\Http\Controllers\Api\CastMemberController;
use App\Http\Requests\StoreCastMemberRequest;
use App\Http\Requests\UpdateCastMemberRequest;
use App\Models\CastMember;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\CreateCastMemberUseCase;
use Core\UseCase\CastMember\DeleteCastMemberUseCase;
use Core\UseCase\CastMember\ListCastMembersUseCase;
use Core\UseCase\CastMember\ListCastMemberUseCase;
use Core\UseCase\CastMember\UpdateCastMemberUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\TestCase;

class CastMemberControllerTest extends TestCase
{
    private CastMemberRepositoryInterface $repository;
    private CastMemberController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CastMemberEloquentRepository(new CastMember());
        $this->controller = new CastMemberController();
    }

    public function testIndex()
    {
        $useCase = new ListCastMembersUseCase($this->repository);

        $response = $this->controller->index(new Request(), $useCase);
        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertEmpty($response->resource);
        $this->assertArrayHasKey("meta", $response->additional);
    }

    public function testStore()
    {
        $useCase = new CreateCastMemberUseCase($this->repository);
        $request = new StoreCastMemberRequest();
        $request->headers->set("content-type", "application/json");
        $request->setJson(new ParameterBag([
                "name" => "test",
                "type" => 1
            ])
        );

        $response = $this->controller->store($request, $useCase);
        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function testUpdate()
    {
        $castMember = CastMember::factory()->create();

        $useCase = new UpdateCastMemberUseCase($this->repository);
        $request = new UpdateCastMemberRequest();
        $request->headers->set("content-type", "application/json");
        $request->setJson(new ParameterBag([
                "name" => "edited name",
                "type" => 1
            ])
        );

        $response = $this->controller->update($request, $castMember, $useCase);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function testShow()
    {
        $castMember = CastMember::factory()->create();

        $useCase = new ListCastMemberUseCase($this->repository);

        $response = $this->controller->show($castMember, $useCase);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function testDelete()
    {
        $castMember = CastMember::factory()->create();

        $useCase = new DeleteCastMemberUseCase($this->repository);

        $response = $this->controller->destroy($castMember, $useCase);
        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}
