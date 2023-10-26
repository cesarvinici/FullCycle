<?php

namespace Core\UseCase\Category\DTO\Insert;

class InsertCategoryOutputDto
{
    public function __construct(
        public string $id,
        public $name,
        public string $description = "",
        public bool $is_active = true,
        public string $created_at = "",
        public string $updated_at = "",
    ) { }





}
