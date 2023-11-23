<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\DTO\CreateCastMemberInputDto;
use Core\UseCase\CastMember\DTO\CreateCastMemberOutputDto;
use Core\UseCase\CastMember\DTO\ListCastMember\ListCastMemberInputDto;
use Core\UseCase\CastMember\DTO\ListCastMember\ListCastMemberOutputDto;

class ListCastMemberUseCase
{
    private CastMemberRepositoryInterface $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(ListCastMemberInputDto $inputDto): ListCastMemberOutputDto
    {

        $castMember = $this->repository->findById($inputDto->id);

        return new ListCastMemberOutputDto(
            id: $castMember->id(),
            name: $castMember->name,
            type: $castMember->type->value,
            createdAt: $castMember->createdAt(),
            updatedAt: $castMember->updatedAt()
        );
    }
}
