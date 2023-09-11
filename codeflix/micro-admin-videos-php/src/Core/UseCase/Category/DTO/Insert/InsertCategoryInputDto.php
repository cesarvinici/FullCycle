<?php

namespace Core\UseCase\Category\DTO\Insert;

class InsertCategoryInputDto
{
    public function __construct(
        public $name,
        public string $description = "",
        public bool $isActive = true,
    ) { }





}