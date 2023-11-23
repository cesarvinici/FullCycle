<?php

namespace Core\UseCase\CastMember\DTO\Create;

class CreateCastMemberInputDto
{
    public function __construct(
        public string $name,
        public int $type,
    ) { }
}
