<?php

namespace Core\UseCase\CastMember\DTO\Create;

use Core\Domain\Enum\CastMemberType;

class CreateCastMemberOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public int $type,
        public string $createdAt,
        public string $updatedAt,
    ) { }
}