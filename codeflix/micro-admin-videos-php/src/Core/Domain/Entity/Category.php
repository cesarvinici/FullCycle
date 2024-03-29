<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MagicMethodsTrait;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class Category
{
    use MagicMethodsTrait;

    public function __construct(
        protected Uuid|string $id = "",
        protected string $name = "",
        protected string $description = "",
        protected bool $isActive = true,
        protected DateTime|string $createdAt = "",
        protected DateTime|string $updatedAt = "",
    )
    {
        $this->id = $this->id
            ? new Uuid($this->id)
            : Uuid::random();

        $this->createdAt = $this->createdAt
            ? new DateTime($this->createdAt)
            : new DateTime();

        $this->updatedAt = $this->updatedAt
            ? new DateTime($this->updatedAt)
            : new DateTime();


        $this->validate();
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function disable(): void
    {
        $this->isActive = false;
    }

    public function update(string $name, string $description = ""): void
    {
        $this->name = $name;
        $this->description = $description;
        $this->updatedAt = new DateTime();

        $this->validate();
    }

    private function validate()
    {
       DomainValidation::strMinLength($this->name, 3, "Name should not be less than 3");
       DomainValidation::strMaxLength($this->name, 255, "Name should not be greater than 255");
       DomainValidation::StrNullOrMaxLength($this->description, 255, "Description should not be greater than 255");
    }
}
