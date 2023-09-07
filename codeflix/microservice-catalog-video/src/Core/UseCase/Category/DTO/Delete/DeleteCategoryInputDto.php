<?php

namespace Core\UseCase\Category\DTO\Delete;

class DeleteCategoryInputDto
{
    public function __construct(
        public string $id
    ) {}
}