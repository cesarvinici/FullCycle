<?php


namespace Core\Domain\Repository;

use Core\Domain\Entity\Entity;
use Core\Domain\Entity\Genre;
use Core\Domain\Repository\PaginationInterface;

interface EntityRepositoryInterface
{
    public function insert(Entity $entity): Entity;
    public function findAll(string $filter = "", string $order = "DESC"): array;
    public function findById(string $id): Entity;
    public function paginate(?string $filter = "", string $order = "DESC", int $page = 1, int $perPage = 15): PaginationInterface;
    public function update(Entity $entity): Entity;
    public function delete(string $id): bool;
}
