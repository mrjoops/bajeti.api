<?php

declare(strict_types=1);

namespace App\Tests\Exception;

use App\Exception\RangeNotSatisfiableHttpException;
use App\Http\Range;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

#[CoversClass(RangeNotSatisfiableHttpException::class)]
#[UsesClass(Range::class)]
class RangeNotSatisfiableHttpExceptionTest extends TestCase
{
    public function testValidRange(): void
    {
        $exception = new RangeNotSatisfiableHttpException(new Range('items', 0, 10, 20));
        self::assertEquals(Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE, $exception->getStatusCode());
        self::assertArrayHasKey('Content-Range', $exception->getHeaders());
    }

    public function testInvalidRange(): void
    {
        $exception = new RangeNotSatisfiableHttpException(new Range());
        self::assertEquals(Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE, $exception->getStatusCode());
        self::assertArrayNotHasKey('Content-Range', $exception->getHeaders());
    }
}
