<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\DTO\Delete\DeleteCategoryInputDto;
use Core\UseCase\Category\DTO\Delete\DeleteCategoryOutputDto;

class DeleteCategoryUseCase
{
    private CategoryRepositoryInterface $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(DeleteCategoryInputDto $input): DeleteCategoryOutputDto
    {
        $response = $this->repository->delete($input->id);

        return new DeleteCategoryOutputDto($response);
    }
}
