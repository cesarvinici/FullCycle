<?php

namespace Core\UseCase\Category;

use App\Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\DTO\Update\UpdateCategoryInputDto;
use Core\UseCase\Category\DTO\Update\UpdateCategoryOutputDto;

class UpdateCategoryUseCase
{
    private CategoryRepositoryInterface $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(UpdateCategoryInputDto $input): UpdateCategoryOutputDto
    {
        $category = $this->repository->update($input);

        return new UpdateCategoryOutputDto(
            id: $category->id(),
            name: $category->name,
            description: $category->description,
            is_active: $category->isActive
        );
    }
}