<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MagicMethodsTrait;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\Uuid;
use Datetime;

class Genre
{
    use MagicMethodsTrait;

    public function __construct(
        protected string $name,
        protected Uuid|string $id = "",
        protected bool $isActive = true,
        protected array $categoriesId = [],
        protected ?Datetime $createdAt = null,
        protected ?Datetime $updatedAt = null,
    )
    {
        $this->id = $id === "" ? Uuid::random() : $id;
        $this->createdAt = $createdAt ?? new Datetime();
        $this->updatedAt = $updatedAt ?? new Datetime();

        $this->validate();
    }

    public function update(string $name): void
    {
        $this->name = $name;

        $this->validate();
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }

    public function addCategory(string $categoryId): void
    {
        if (! in_array($categoryId, $this->categoriesId))
        {
            $this->categoriesId[] = $categoryId;
        }
    }

    public function removeCategory(string $categoryToBeRemoved): void
    {
        $this->categoriesId = array_filter($this->categoriesId, fn($id) => $id !== $categoryToBeRemoved);
    }

    private function validate()
    {
        DomainValidation::strMinLength($this->name, 3, "Name should not be less than 3");
        DomainValidation::strMaxLength($this->name, 255, "Name should not be greater than 255");
    }
}
