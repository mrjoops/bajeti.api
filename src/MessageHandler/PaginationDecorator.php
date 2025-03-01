<?php

declare(strict_types=1);

namespace App\MessageHandler;

class PaginationDecorator implements \JsonSerializable
{
    public function __construct(
        private readonly \JsonSerializable $dto,
        public readonly int $count,
        public readonly int $size
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return $this->dto->jsonSerialize();
    }
}
