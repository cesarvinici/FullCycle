<?php

namespace Core\UseCase\CastMember\DTO\Update;

class UpdateCastMemberInputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public int $type
    ) { }
}
