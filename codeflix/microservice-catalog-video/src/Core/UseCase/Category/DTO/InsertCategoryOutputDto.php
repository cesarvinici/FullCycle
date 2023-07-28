<?php

namespace Core\UseCase\Category\DTO;

class InsertCategoryOutputDto
{
    public function __construct(
        public string $id,
        public $name,
        public string $description = "",
        public bool $is_active = true,
    ) { }





}