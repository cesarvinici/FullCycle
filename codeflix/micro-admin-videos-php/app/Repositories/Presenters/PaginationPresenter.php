<?php

namespace App\Repositories\Presenters;

use Core\Domain\Repository\PaginationInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginationPresenter implements PaginationInterface
{
    private LengthAwarePaginator $paginator;

    protected array $items = [];

    public function __construct(LengthAwarePaginator $paginator)
    {
        $this->paginator = $paginator;
        $this->items = $this->resolveItems($this->paginator->items());
    }

    public function items(): array
    {
        return $this->items;
    }

    public function total(): int
    {
        return $this->paginator->total();
    }

    public function currentPage(): int
    {
        return $this->paginator->currentPage();
    }

    public function firstPage(): int
    {
        return 1;
    }

    public function lastPage(): int
    {
        return $this->paginator->lastPage();
    }

    public function perPage(): int
    {
        return $this->paginator->perPage();
    }

    public function to(): int
    {
        return (int) $this->paginator->firstItem();
    }

    public function from(): int
    {
        return (int) $this->paginator->lastItem();
    }

    private function resolveItems(array $items)
    {
        $response = [];

        foreach ($items as $item) {
            $response[] = (object) $item->toArray();
        }

        return $response;
    }
}
