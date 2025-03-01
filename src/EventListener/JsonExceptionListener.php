<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Http\ProblemDetailsResponse;
use App\Model\Response\Assembler\ExceptionDtoAssembler;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

class JsonExceptionListener
{
    public function __construct(private ExceptionDtoAssembler $dtoAssembler)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        while ($exception instanceof HandlerFailedException) {
            if (is_null($exception->getPrevious())) {
                break;
            }

            $exception = $exception->getPrevious();
        }

        $headers = $exception instanceof HttpException ? $exception->getHeaders() : [];
        $headers['Content-Language'] = $event->getRequest()->getLocale();

        $event->setResponse(new ProblemDetailsResponse($this->dtoAssembler->create($exception), $headers));
    }
}
