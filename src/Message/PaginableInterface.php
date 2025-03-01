<?php

declare(strict_types=1);

namespace App\Message;

interface PaginableInterface
{
    public ?int $limit { get; set; }
    public ?int $offset { get; set; }
}
