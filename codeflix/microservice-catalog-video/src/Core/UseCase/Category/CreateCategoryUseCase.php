<?php

namespace Core\UseCase\Category;

use App\Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Entity\Category;
use Core\UseCase\Category\DTO\InsertCategoryInputDto;
use Core\UseCase\Category\DTO\InsertCategoryOutputDto;
use Unit\UseCase\UseCaseInterface;

class CreateCategoryUseCase
{
    private CategoryRepositoryInterface $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(InsertCategoryInputDto $input): InsertCategoryOutputDto
    {
        $category = new Category(
            name: $input->name,
            description: $input->description,
            isActive: $input->isActive
        );

        $newCategory = $this->repository->insert($category);

        return new InsertCategoryOutputDto(
            id: $newCategory->id(),
            name: $input->name,
            description: $input->description,
            is_active: $input->isActive
        );
    }
}