<?php

declare(strict_types=1);

namespace App\Message;

trait PaginableTrait
{
    public ?int $limit = null;
    public ?int $offset = null;
}
