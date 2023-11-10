<?php

namespace Core\UseCase\Genre\DTO;

class DeleteGenreInputDto
{
    public string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
