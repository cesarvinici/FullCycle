<?php

namespace Core\UseCase\Genre;

use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\Genre\DTO\Update\UpdateGenreInputDto;
use Core\UseCase\Genre\DTO\Update\UpdateGenreOutputDto;
use Core\UseCase\Interfaces\TransactionInterface;

class UpdateGenreUseCase
{

    public function __construct(
        GenreRepositoryInterface $repository,
        TransactionInterface $transaction,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->repository = $repository;
        $this->transaction = $transaction;
        $this->categoryRepository = $categoryRepository;
    }

    public function execute(UpdateGenreInputDto $input): UpdateGenreOutputDto
    {
        $genre = $this->repository->findById($input->id);

        try {

            $this->validateCategoriesId($input->categoriesId);

            $genre->update(
                name: $input->name,
            );

            $input->is_active ? $genre->activate() : $genre->deactivate();

            $categoriesToBeRemoved = array_filter(
                $genre->categoriesId,
                fn ($categoryId) => ! in_array($categoryId, $input->categoriesId)
            );

            collect($categoriesToBeRemoved)->each(fn ($categoryId) => $genre->removeCategory($categoryId));
            collect($input->categoriesId)->each(fn ($categoryId) => $genre->addCategory($categoryId));

            $response = $this->repository->update($genre);

            $this->transaction->commit();

            return new UpdateGenreOutputDto(
                id: $response->id(),
                name: $response->name,
                is_active: $response->isActive,
                categoriesId: $response->categoriesId,
                created_at: $response->createdAt(),
                updated_at: $response->updatedAt()
            );
        } catch (\Throwable $th) {
            $this->transaction->rollBack();
            throw $th;
        }
    }

    private function validateCategoriesId(array $categoriesIdsList): void
    {

        if (count($categoriesIdsList) === 0) {
            throw new NotFoundException("You must specify a category");
        }

        $categoriesInDatabase = $this->categoryRepository->getCategoriesIds($categoriesIdsList);
        $missingCategories = array_diff($categoriesIdsList, $categoriesInDatabase);

        if (count($missingCategories) > 0) {
            $categoriesNotFound = implode(",", $missingCategories);
            throw new NotFoundException("Categories not found: {$categoriesNotFound}");
        }
    }
}
