<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\DTO\ListCastMembers\ListCastMembersInputDto;
use Core\UseCase\CastMember\DTO\ListCastMembers\ListCastMembersOutputDto;

class ListCastMembersUseCase
{
    private CastMemberRepositoryInterface $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(ListCastMembersInputDto $inputDto): ListCastMembersOutputDto
    {
        $response = $this->repository->paginate(
            $inputDto->filter,
            $inputDto->order,
            $inputDto->page,
            $inputDto->perPage
        );

        return new ListCastMembersOutputDto(
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
