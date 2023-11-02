<?php

namespace Core\UseCase\Genre\DTO\Create;

class CreateGenreInputDto
{
    public function __construct(
        public string $name,
        public bool $is_active = true,
        public array $categoriesId = []
    )
    { }
}
