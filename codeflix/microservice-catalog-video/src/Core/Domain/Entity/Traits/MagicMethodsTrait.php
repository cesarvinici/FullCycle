<?php

namespace Core\Domain\Entity\Traits;

trait MagicMethodsTrait
{
    public function __get(string $property)
    {
        if (isset($this->{$property})) {
            return $this->{$property};
        }
        $className = get_class($this);
        throw new \Exception("Attribute {$property} not found in class {$className}");
    }

    public function id(): string
    {
        return (string) $this->id;
    }

    public function createdAt(): string
    {
        return $this->createdAt->format("Y-m-d H:i:s");
    }

    public function updatedAt(): string
    {
        return $this->updatedAt->format("Y-m-d H:i:s");
    }



}