<?php

declare(strict_types=1);

namespace App\Tests\Model\Response\Assembler;

use App\Model\Response\Assembler\ExceptionDtoAssembler;
use App\Model\Response\ExceptionDto;
use App\Model\Response\ProblemDetailsDto;
use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[CoversClass(ExceptionDtoAssembler::class)]
#[UsesClass(ProblemDetailsDto::class)]
final class ExceptionDtoAssemblerTest extends TestCase
{
    public function testSimpleException(): void
    {
        $assembler = new ExceptionDtoAssembler(false);
        $actual = $assembler->exceptionToStatusCode(new Exception());
        self::assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $actual);
    }

    public function testExceptionWithCode(): void
    {
        $assembler = new ExceptionDtoAssembler(false);
        $actual = $assembler->exceptionToStatusCode(new Exception('', Response::HTTP_NOT_FOUND));
        self::assertEquals(Response::HTTP_NOT_FOUND, $actual);
    }

    public function testHttpException(): void
    {
        $assembler = new ExceptionDtoAssembler(false);
        $actual = $assembler->exceptionToStatusCode(new NotFoundHttpException());
        self::assertEquals(Response::HTTP_NOT_FOUND, $actual);
    }

    public function testFactoryDebug(): void
    {
        $dto = new ExceptionDtoAssembler(true)->create(new Exception());
        self::assertTrue(isset($dto->file));
    }

    public function testFactoryNoDebug(): void
    {
        $dto = new ExceptionDtoAssembler(false)->create(new Exception());
        self::assertFalse(isset($dto->file));
    }
}
