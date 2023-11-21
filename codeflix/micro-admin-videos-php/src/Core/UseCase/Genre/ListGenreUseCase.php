<?php

namespace Core\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\Genre\DTO\ListGenre\ListGenreInputDto;
use Core\UseCase\Genre\DTO\ListGenre\ListGenreOutputDto;

class ListGenreUseCase
{
    private GenreRepositoryInterface $repository;

    public function __construct(GenreRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(ListGenreInputDto $input): ListGenreOutputDto
    {
        $response = $this->repository->findById($input->id);

        return new ListGenreOutputDto(
            id: $response->id(),
            name: $response->name,
            is_active: $response->isActive,
            categoriesId: $response->categoriesId,
            created_at: $response->createdAt(),
            updated_at: $response->updatedAt()
        );
    }
}
