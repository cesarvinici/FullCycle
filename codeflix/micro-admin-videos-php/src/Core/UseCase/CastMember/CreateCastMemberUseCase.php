<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\DTO\Create\CreateCastMemberInputDto;
use Core\UseCase\CastMember\DTO\Create\CreateCastMemberOutputDto;

class CreateCastMemberUseCase
{
    private CastMemberRepositoryInterface $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CreateCastMemberInputDto $inputDto): CreateCastMemberOutputDto
    {
        $entity = new CastMember (
            name: $inputDto->name,
            type: CastMemberType::from($inputDto->type)
        );

        $response = $this->repository->insert($entity);

        return new CreateCastMemberOutputDto(
            id: $response->id(),
            name: $response->name,
            type: $response->type->value,
            created_at: $response->createdAt(),
            updated_at: $response->updatedAt()
        );
    }
}
