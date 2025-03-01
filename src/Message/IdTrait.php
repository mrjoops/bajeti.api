<?php

declare(strict_types=1);

namespace App\Message;

use Symfony\Component\Uid\Uuid;

trait IdTrait
{
    public Uuid $id;
}
