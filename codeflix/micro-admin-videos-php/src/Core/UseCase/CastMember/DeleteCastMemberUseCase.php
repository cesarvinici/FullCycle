<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\DTO\Delete\DeleteCastMemberInputDto;
use Core\UseCase\CastMember\DTO\Delete\DeleteCastMemberOutputDto;
use Core\UseCase\CastMember\DTO\Update\UpdateCastMemberInputDto;
use Core\UseCase\CastMember\DTO\Update\UpdateCastMemberOutputDto;

class DeleteCastMemberUseCase
{
    private CastMemberRepositoryInterface $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(DeleteCastMemberInputDto $inputDto): DeleteCastMemberOutputDto
    {
        $castMemberEntity = $this->repository->findById($inputDto->id);

        return new DeleteCastMemberOutputDto($this->repository->delete($inputDto->id));
    }
}
