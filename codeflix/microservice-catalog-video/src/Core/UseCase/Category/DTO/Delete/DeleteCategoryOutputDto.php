<?php

namespace Core\UseCase\Category\DTO\Delete;

class DeleteCategoryOutputDto
{
    public function __construct(
        public bool $success
    ) {}
}