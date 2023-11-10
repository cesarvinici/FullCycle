<?php

namespace Core\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\Genre\DTO\DeleteGenreInputDto;
use Core\UseCase\Genre\DTO\Update\DeleteGenreOutputDto;

class DeleteGenreUseCase
{

    private GenreRepositoryInterface $repository;

    public function __construct(GenreRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(DeleteGenreInputDto $input): DeleteGenreOutputDto
    {
        $entity = $this->repository->findById($input->id);
        return new DeleteGenreOutputDto(
            $this->repository->delete($entity->id())
        );
    }
}
