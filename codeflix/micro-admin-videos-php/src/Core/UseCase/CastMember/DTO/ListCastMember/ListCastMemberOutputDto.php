<?php

namespace Core\UseCase\CastMember\DTO\ListCastMember;

class ListCastMemberOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public int $type,
        public string $createdAt,
        public string $updatedAt,
    ) { }
}
