<?php

declare(strict_types=1);

namespace App\Http;

use App\Exception\RangeNotSatisfiableHttpException;
use App\MessageHandler\PaginationDecorator;
use Symfony\Component\HttpFoundation\JsonResponse;

class PaginatedJsonResponse extends JsonResponse
{
    /**
     * @param array<string, string> $headers
     */
    public function __construct(PaginationDecorator $data, string $unit = 'items', int $offset = 0, array $headers = [])
    {
        $range = new Range($unit, $offset, $data->count, $data->size);

        if (! $range->valid()) {
            throw new RangeNotSatisfiableHttpException($range);
        }

        if ($data->count === $data->size) {
            parent::__construct($data, self::HTTP_OK, array_merge($range->toAcceptRangesHeader(), $headers));
            return;
        }

        parent::__construct($data, self::HTTP_PARTIAL_CONTENT, array_merge($range->toContentRangeHeader(), $headers));
    }
}
