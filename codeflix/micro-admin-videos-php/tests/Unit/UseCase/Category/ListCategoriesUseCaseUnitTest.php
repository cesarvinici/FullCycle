<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\UseCase\Category\DTO\ListCategories\ListCategoriesInputDto;
use Core\UseCase\Category\DTO\ListCategories\ListCategoriesOutputDto;
use Core\UseCase\Category\ListCategoriesUseCase;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class ListCategoriesUseCaseUnitTest extends TestCase
{

    public function testListCategories()
    {
        $item = new stdClass();
        $item->id = "1";
        $item->name = "Category Name";
        $item->description = "Category Description";
        $item->is_active = true;

        $mockPaginate = $this->mockPagination([$item]);

        $mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mockRepository->shouldReceive('paginate')
            ->andReturn($mockPaginate);

        $mockInputDto = Mockery::mock(
            ListCategoriesInputDto::class,
            [
                null,
                'DESC',
                1,
                15
            ]
        );

        $useCase = new ListCategoriesUseCase($mockRepository);
        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(ListCategoriesOutputDto::class, $response);
        $this->assertInstanceOf(stdClass::class, $response->items[0]);
        $this->assertEquals(1, $response->total);
        $this->assertCount(1, $response->items);
        $this->assertEquals(1, $response->currentPage);
        $this->assertEquals(1, $response->firstPage);
        $this->assertEquals(1, $response->lastPage);
        $this->assertEquals(15, $response->perPage);
        $this->assertEquals(0, $response->to);
        $this->assertEquals(0, $response->from);
    }


    public function testListCategoriesEmpty()
    {

        $mockPaginate = $this->mockPagination();

        $mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mockRepository->shouldReceive('paginate')
            ->andReturn($mockPaginate);

        $mockInputDto = Mockery::mock(
            ListCategoriesInputDto::class,
            [
                null,
                'DESC',
                1,
                15
            ]
        );

        $useCase = new ListCategoriesUseCase($mockRepository);
        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(ListCategoriesOutputDto::class, $response);
        $this->assertEquals(0, $response->total);
        $this->assertCount(0, $response->items);
        $this->assertEquals(1, $response->currentPage);
        $this->assertEquals(1, $response->firstPage);
        $this->assertEquals(1, $response->lastPage);
        $this->assertEquals(15, $response->perPage);
        $this->assertEquals(0, $response->to);
        $this->assertEquals(0, $response->from);
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
