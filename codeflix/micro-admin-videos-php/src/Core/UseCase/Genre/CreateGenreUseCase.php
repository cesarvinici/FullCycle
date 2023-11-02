<?php

namespace Core\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\Genre\DTO\Create\CreateGenreInputDto;
use Core\UseCase\Genre\DTO\Create\CreateGenreOutputDto;
use Core\UseCase\Interfaces\TransactionInterface;

class CreateGenreUseCase
{
    private GenreRepositoryInterface $repository;
    private TransactionInterface $transaction;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(
        GenreRepositoryInterface $repository,
        TransactionInterface $transaction,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->repository = $repository;
        $this->transaction = $transaction;
        $this->categoryRepository = $categoryRepository;
    }

    public function execute(CreateGenreInputDto $inputDto): CreateGenreOutputDto
    {

       try {

           $entity = new Genre(
               name: $inputDto->name,
               isActive: $inputDto->is_active,
               categoriesId: $this->validateCategoriesId($inputDto->categoriesId)
           );

           $response = $this->repository->insert($entity);

           $this->transaction->commit();

           return new CreateGenreOutputDto(
               id: $response->id(),
               name: $response->name(),
               is_active: $response->isActive(),
               categoriesId: $response->categoriesId(),
               created_at: $response->createdAt()
           );
       } catch (\Throwable $th) {
           $this->transaction->rollback();
           throw $th;
       }
    }

    public function validateCategoriesId(array $categoriesIdsList)
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

        return $categoriesIdsList;

    }

}
