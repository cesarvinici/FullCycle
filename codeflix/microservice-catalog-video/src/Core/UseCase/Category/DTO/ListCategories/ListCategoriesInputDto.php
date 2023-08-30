<?php

namespace Core\UseCase\Category\DTO\ListCategories;

class ListCategoriesInputDto
{
    public function __construct(
        public ?string $filter,
        public string $order = 'DESC',
        public int $page = 1,
        public int $perPage = 15,
    ) { }
}