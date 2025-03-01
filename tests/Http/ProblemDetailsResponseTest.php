<?php

declare(strict_types=1);

namespace App\Tests\Http;

use App\Http\ProblemDetailsResponse;
use App\Model\Response\ProblemDetailsDto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

#[CoversClass(ProblemDetailsResponse::class)]
#[UsesClass(ProblemDetailsDto::class)]
class ProblemDetailsResponseTest extends TestCase
{
    public function testSimple(): void
    {
        $dto = new ProblemDetailsDto('Test');
        $dto->status = Response::HTTP_INTERNAL_SERVER_ERROR;
        $response = new ProblemDetailsResponse($dto);

        self::assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        self::assertEquals(ProblemDetailsResponse::MIME_TYPE_JSON_PROBLEM, $response->headers->get('Content-Type'));
    }
}
