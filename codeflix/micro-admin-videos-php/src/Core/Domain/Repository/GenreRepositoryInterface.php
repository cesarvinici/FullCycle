<?php


namespace Core\Domain\Repository;

use Core\Domain\Entity\Genre;
use Core\Domain\Repository\PaginationInterface;

interface GenreRepositoryInterface
{
    public function insert(Genre $entity): Genre;
    public function findAll(string $filter = "", string $order = "DESC"): array;
    public function findById(string $id): Genre;
    public function paginate(?string $filter = "", string $order = "DESC", int $page = 1, int $perPage = 15): PaginationInterface;
    public function update(Genre $genre): Genre;
    public function delete(string $id): bool;
}
