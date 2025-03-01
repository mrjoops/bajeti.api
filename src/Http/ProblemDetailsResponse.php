<?php

declare(strict_types=1);

namespace App\Http;

use App\Model\Response\ProblemDetailsDto;
use Symfony\Component\HttpFoundation\Response;

class ProblemDetailsResponse extends Response
{
    public const MIME_TYPE_JSON_PROBLEM = 'application/problem+json';

    /**
     * @param array<string, string> $headers
     */
    public function __construct(ProblemDetailsDto $dto, array $headers = [])
    {
        parent::__construct(
            json_encode($dto) ?: '',
            $dto->status ?? Response::HTTP_INTERNAL_SERVER_ERROR,
            $headers
        );
        $this->headers->set('Content-Type', self::MIME_TYPE_JSON_PROBLEM);
    }
}
