<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MagicMethodsTrait;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class CastMember
{
    use MagicMethodsTrait;

    public function __construct(
        protected string $name,
        protected CastMemberType $type = CastMemberType::ACTOR,
        protected ?Uuid $id = null,
        protected ?Datetime $createdAt = null,
        protected ?Datetime $updatedAt = null,
    )
    {
        $this->id = $id ?? Uuid::random();
        $this->createdAt = $createdAt ?? new Datetime();
        $this->updatedAt = $updatedAt ?? new Datetime();

        $this->validate();
    }

    public function update(?string $name, ?CastMemberType $type): void
    {
        $this->name = $name ?? $this->name;
        $this->type = $type ?? $this->type;

        $this->validate();
    }

    private function validate()
    {
        DomainValidation::strMinLength($this->name, 3, "Name should not be less than 3");
        DomainValidation::strMaxLength($this->name, 255, "Name should not be greater than 255");
    }
}
