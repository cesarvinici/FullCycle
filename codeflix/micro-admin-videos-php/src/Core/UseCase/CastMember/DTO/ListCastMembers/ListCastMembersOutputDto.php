<?php

namespace Core\UseCase\CastMember\DTO\ListCastMembers;

class ListCastMembersOutputDto
{
    public function __construct(
        public array $items,
        public int $total,
        public int $currentPage,
        public int $firstPage,
        public int $lastPage,
        public int $perPage,
        public int $to,
        public int $from,
    )
    {

    }
}
