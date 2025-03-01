<?php

declare(strict_types=1);

namespace App\Model\Response\Assembler;

use App\Model\Response\ExceptionDto;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionDtoAssembler
{
    public function __construct(#[Autowire('kernel.debug')] private bool $debug)
    {
    }

    /**
     * @SuppressWarnings("PHPMD.UndefinedVariable")
     */
    public function create(\Throwable $exception): ExceptionDto
    {
        $status = $this->exceptionToStatusCode($exception);
        $dto = new ExceptionDto(Response::$statusTexts[$status]);
        $dto->detail = $exception->getMessage();
        $dto->status = $status;

        if ($this->debug) {
            $dto->file = $exception->getFile();
            $dto->line = $exception->getLine();
            $dto->trace = $exception->getTrace();
        }

        return $dto;
    }

    public function exceptionToStatusCode(\Throwable $exception): int
    {
        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        if ($exception->getCode() >= 400 && $exception->getCode() < 500) {
            return intval($exception->getCode());
        }

        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}
