<?php

namespace Core\Domain\ValueObject;

use Core\Domain\Enum\MediaStatus;

class Media
{
    public function __construct(
        public readonly string $path,
        public readonly MediaStatus $status,
        public readonly string $encodedPath
    ) { }
}
