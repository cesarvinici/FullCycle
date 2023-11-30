<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\DTO\Update\UpdateCastMemberInputDto;
use Core\UseCase\CastMember\DTO\Update\UpdateCastMemberOutputDto;

class UpdateCastMemberUseCase
{
    private CastMemberRepositoryInterface $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(UpdateCastMemberInputDto $inputDto): UpdateCastMemberOutputDto
    {
        $castMemberEntity = $this->repository->findById($inputDto->id);
        $castMemberEntity->update($inputDto->name, CastMemberType::from($inputDto->type));
        $response = $this->repository->update($castMemberEntity);


        return new UpdateCastMemberOutputDto(
            $response->id(),
            $response->name,
            $response->type->value,
            $response->createdAt(),
            $response->updatedAt()
        );
    }
}
