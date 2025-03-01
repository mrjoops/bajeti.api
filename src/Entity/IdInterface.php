<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

interface IdInterface
{
    public ?int $id { get; set; }
    public ?Uuid $uuid { get; set; }
    public function generateUuid(): void;
}
