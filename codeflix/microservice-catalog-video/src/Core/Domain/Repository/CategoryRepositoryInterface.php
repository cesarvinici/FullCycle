<?php


namespace App\Core\Domain\Repository;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\PaginationInterface;

interface CategoryRepositoryInterface
{
    public function insert(Category $category): Category;

    public function findAll(string $filter = "", string $order = "DESC"): array;
    public function findById(string $id): Category;
    public function paginate(string $filter = "", string $order = "DESC", int $page = 1, int $perPage = 15): PaginationInterface;
    public function update(Category $category): Category;
    public function delete(string $id): bool;
    public function toCategory(object $data): Category;
}