<?php

namespace Tests\Traits;

use Core\Domain\Repository\PaginationInterface;
use Mockery\MockInterface;
use Mockery;
trait MockPaginationTrait
{
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
