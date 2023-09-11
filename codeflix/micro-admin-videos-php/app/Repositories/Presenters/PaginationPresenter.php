<?php

namespace App\Repositories\Presenters;

use Core\Domain\Repository\PaginationInterface;

class PaginationPresenter implements PaginationInterface
{
    private array $items;
    private int $total;
    private int $currentPage;
    private int $perPage;
    private int $lastPage;
    private int $firstPage;
    private int $from;
    private int $to;

    public function __construct(
        array $items,
        int $total,
        int $currentPage,
        int $perPage,
        int $lastPage,
        int $firstPage,
        int $from,
        int $to
    ) {
        $this->items = $items;
        $this->total = $total;
        $this->currentPage = $currentPage;
        $this->perPage = $perPage;
        $this->lastPage = $lastPage;
        $this->firstPage = $firstPage;
        $this->from = $from;
        $this->to = $to;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function currentPage(): int
    {
        return $this->currentPage;
    }

    public function firstPage(): int
    {
        return $this->firstPage;
    }

    public function lastPage(): int
    {
        return $this->lastPage;
    }

    public function perPage(): int
    {
        return $this->perPage;
    }

    public function to(): int
    {
        return $this->to;
    }

    public function from(): int
    {
        return $this->from;
    }
}
