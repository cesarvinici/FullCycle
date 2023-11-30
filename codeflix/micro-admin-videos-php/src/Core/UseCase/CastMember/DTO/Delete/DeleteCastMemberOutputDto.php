<?php

namespace Core\UseCase\CastMember\DTO\Delete;

class DeleteCastMemberOutputDto
{
    public bool $success;

    public function __construct(bool $success)
    {
        $this->success = $success;
    }
}
