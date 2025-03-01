<?php

declare(strict_types=1);

namespace App\Tests\Http;

use App\Exception\RangeNotSatisfiableHttpException;
use App\Http\PaginatedJsonResponse;
use App\Http\Range;
use App\MessageHandler\PaginationDecorator;
use App\Model\Response\ProblemDetailsDto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PaginatedJsonResponse::class)]
#[UsesClass(PaginationDecorator::class)]
#[UsesClass(ProblemDetailsDto::class)]
#[UsesClass(Range::class)]
#[UsesClass(RangeNotSatisfiableHttpException::class)]
class PaginatedJsonResponseTest extends TestCase
{
    public function testComplete(): void
    {
        $response = new PaginatedJsonResponse(new PaginationDecorator(new ProblemDetailsDto('Test'), 5, 5));
        self::assertEquals(PaginatedJsonResponse::HTTP_OK, $response->getStatusCode());
    }

    public function testException(): void
    {
        $this->expectException(RangeNotSatisfiableHttpException::class);
        new PaginatedJsonResponse(new PaginationDecorator(new ProblemDetailsDto('Test'), 5, -1));
    }

    public function testPartial(): void
    {
        $response = new PaginatedJsonResponse(new PaginationDecorator(new ProblemDetailsDto('Test'), 5, 10));
        self::assertEquals(PaginatedJsonResponse::HTTP_PARTIAL_CONTENT, $response->getStatusCode());
    }
}
