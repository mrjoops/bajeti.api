<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\SortableInterface;

trait OrderByCreatorTrait
{
    /**
     * @return array<string, string>|null
     */
    public function createOrderByFromMessage(SortableInterface $message): ?array
    {
        if (is_null($message->sort)) {
            return null;
        }

        $count = preg_match_all('/(\w+):(asc|desc)/i', $message->sort, $matches, PREG_SET_ORDER);

        if (false === $count || 0 === $count) {
            return null;
        }

        return array_reduce($matches, function (array $orderBy, array $match) {
            $orderBy[$match[1]] = $match[2];

            return $orderBy;
        }, []);
    }
}
