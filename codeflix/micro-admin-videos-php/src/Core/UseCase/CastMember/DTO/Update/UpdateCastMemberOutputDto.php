<?php

namespace Core\UseCase\CastMember\DTO\Update;

class UpdateCastMemberOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public int $type,
        public string $createdAt,
        public string $updatedAt
    ) { }
}
