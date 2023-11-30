<?php

namespace Core\UseCase\CastMember\DTO\ListCastMembers;

class ListCastMembersInputDto
{
    public function __construct(
        public ?string $filter,
        public string $order = 'DESC',
        public int $page = 1,
        public int $perPage = 15,
    ) { }
}
