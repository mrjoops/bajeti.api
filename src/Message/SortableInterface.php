<?php

declare(strict_types=1);

namespace App\Message;

interface SortableInterface
{
    public ?string $sort { get; set; }
}
