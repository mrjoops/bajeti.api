<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\SortableInterface;

interface OrderByCreatorInterface
{
    /**
     * @return array<string, string>|null
     */
    public function createOrderByFromMessage(SortableInterface $message): ?array;
}
