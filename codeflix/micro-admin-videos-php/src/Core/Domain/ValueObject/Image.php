<?php

namespace Core\Domain\ValueObject;

class Image
{
    public function __construct(
        private string $path
    ) { }

    public function path(): ?string
    {
        return $this->path;
    }
}
