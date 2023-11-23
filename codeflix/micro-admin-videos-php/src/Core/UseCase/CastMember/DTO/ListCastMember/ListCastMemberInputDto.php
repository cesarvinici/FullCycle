<?php

namespace Core\UseCase\CastMember\DTO\ListCastMember;

class ListCastMemberInputDto
{
    public string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
