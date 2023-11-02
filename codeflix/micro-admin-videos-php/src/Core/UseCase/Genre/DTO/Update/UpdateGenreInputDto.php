<?php

namespace Core\UseCase\Genre\DTO\Update;

class UpdateGenreInputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public bool $is_active = true,
        public array $categoriesId = []
    )
    { }
}
