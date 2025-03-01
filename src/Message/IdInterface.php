<?php

declare(strict_types=1);

namespace App\Message;

use Symfony\Component\Uid\Uuid;

interface IdInterface
{
    public Uuid $id { get; set; }
}
