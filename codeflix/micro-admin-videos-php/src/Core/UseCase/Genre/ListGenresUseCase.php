<?php

namespace Core\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\Genre\DTO\ListGenres\ListGenresInputDto;
use Core\UseCase\Genre\DTO\ListGenres\ListGenresOutputDto;

class ListGenresUseCase
{
    private GenreRepositoryInterface $repository;

    public function __construct(GenreRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(ListGenresInputDto $inputDto): ListGenresOutputDto
    {
        $response = $this->repository->paginate(
            $inputDto->filter,
            $inputDto->order,
            $inputDto->page,
            $inputDto->perPage
        );

        return new ListGenresOutputDto(
            $response->items(),
            $response->total(),
            $response->currentPage(),
            $response->firstPage(),
            $response->lastPage(),
            $response->perPage(),
            $response->to(),
            $response->from(),
        );
    }
}
