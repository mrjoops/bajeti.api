<?php

declare(strict_types=1);

namespace App\Exception;

use App\Http\Range;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RangeNotSatisfiableHttpException extends HttpException
{
    /**
     * @param array<string, string> $headers
     */
    public function __construct(
        Range $range,
        string $message = '',
        ?\Throwable $previous = null,
        int $code = 0,
        array $headers = [],
    ) {
        parent::__construct(
            416,
            $message,
            $previous,
            array_merge($headers, $range->toContentRangeHeader()),
            $code,
        );
    }
}
