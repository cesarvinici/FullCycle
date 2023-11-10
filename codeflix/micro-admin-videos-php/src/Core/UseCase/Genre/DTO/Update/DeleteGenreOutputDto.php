<?php

namespace Core\UseCase\Genre\DTO\Update;

class DeleteGenreOutputDto
{
    public bool $success;

    public function __construct(bool $success)
    {
        $this->success = $success;
    }
}
