<?php


namespace Core\Domain\Repository;

use Core\Domain\Entity\CastMember;
use Core\Domain\Repository\PaginationInterface;

interface CastMemberRepositoryInterface
{
    public function insert(CastMember $entity): CastMember;
    public function findAll(string $filter = "", string $order = "DESC"): array;
    public function findById(string $id): CastMember;
    public function paginate(?string $filter = "", string $order = "DESC", int $page = 1, int $perPage = 15): PaginationInterface;
    public function update(CastMember $castMember): CastMember;
    public function delete(string $id): bool;
}
