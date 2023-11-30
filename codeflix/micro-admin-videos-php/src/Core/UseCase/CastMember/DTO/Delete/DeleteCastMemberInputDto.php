<?php

namespace Core\UseCase\CastMember\DTO\Delete;

class DeleteCastMemberInputDto
{
    public string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
