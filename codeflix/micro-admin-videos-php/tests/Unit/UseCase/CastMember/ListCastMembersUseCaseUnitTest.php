<?php

namespace Tests\Feature\UseCase\CastMember;

use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\DTO\ListCastMembers\ListCastMembersInputDto;
use Core\UseCase\CastMember\DTO\ListCastMembers\ListCastMembersOutputDto;
use Core\UseCase\CastMember\ListCastMembersUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\MockPaginationTrait;
use Mockery;
use stdClass;

class ListCastMembersUseCaseUnitTest extends TestCase
{
    use MockPaginationTrait;

    private $mockCastMemberRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockCastMemberRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
    }

    public function testListCastMembers()
    {
        $item = new stdClass();
        $item->id = "1";
        $item->name = "Category Name";
        $item->type = CastMemberType::DIRECTOR;

        $item2 = new stdClass();
        $item2->id = "2";
        $item2->name = "Category Name 2";
        $item2->type = CastMemberType::ACTOR;

        $mockPaginate = $this->mockPagination([$item, $item2]);

        $this->mockCastMemberRepository->shouldReceive('paginate')
            ->andReturn($mockPaginate);

        $mockInputDto = Mockery::mock(
            ListCastMembersInputDto::class,
            [
                null,
                'DESC',
                1,
                15
            ]
        );

        $useCase = new ListCastMembersUseCase($this->mockCastMemberRepository);
        $response = $useCase->execute($mockInputDto);
        $this->assertInstanceOf(ListCastMembersOutputDto::class, $response);
        $this->assertInstanceOf(stdClass::class, $response->items[0]);
        $this->assertEquals(2, $response->total);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
