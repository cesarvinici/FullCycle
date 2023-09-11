<?php

namespace Core\UseCase\Category\DTO\Update;

class UpdateCategoryInputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public bool $is_active
    ) { }

}