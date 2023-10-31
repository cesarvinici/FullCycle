<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\UseCase\Genre\DTO\ListGenres\ListGenresInputDto;
use Core\UseCase\Genre\DTO\ListGenres\ListGenresOutputDto;
use Core\UseCase\Genre\ListGenresUseCase;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Mockery;
use stdClass;

class ListGenresUseCaseUnitTest extends TestCase
{
    public function testUseCase()
    {
        $item = new stdClass();
        $item->id = "1";
        $item->name = "Category Name";
        $item->description = "Category Description";
        $item->is_active = true;

        $mockPaginate = $this->mockPagination([$item]);

        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('paginate')
            ->andReturn($mockPaginate);

        $mockInputDto = Mockery::mock(
            ListGenresInputDto::class,
            [
                null,
                'DESC',
                1,
                15
            ]
        );

        $useCase = new ListGenresUseCase($mockRepository);
        $response = $useCase->execute($mockInputDto);
        $this->assertInstanceOf(ListGenresOutputDto::class, $response);
        $this->assertInstanceOf(stdClass::class, $response->items[0]);
        $this->assertEquals(1, $response->total);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    protected function mockPagination(array $items = []): MockInterface
    {
        $mockPaginate = Mockery::mock(stdClass::class, PaginationInterface::class);
        $mockPaginate->shouldReceive('items')->andReturn($items);
        $mockPaginate->shouldReceive('total')->andReturn(count($items));
        $mockPaginate->shouldReceive('currentPage')->andReturn(1);
        $mockPaginate->shouldReceive('firstPage')->andReturn(1);
        $mockPaginate->shouldReceive('lastPage')->andReturn(1);
        $mockPaginate->shouldReceive('perPage')->andReturn(15);
        $mockPaginate->shouldReceive('to')->andReturn(0);
        $mockPaginate->shouldReceive('from')->andReturn(0);

        return $mockPaginate;
    }

}
