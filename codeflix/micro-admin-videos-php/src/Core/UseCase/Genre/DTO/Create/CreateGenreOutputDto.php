<?php

namespace Core\UseCase\Genre\DTO\Create;

class CreateGenreOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public bool $is_active,
        public array $categoriesId,
        public string $created_at,
        public string $updated_at,
    )
    { }
}
