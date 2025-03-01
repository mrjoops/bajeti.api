<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait MessageExtractorTrait
{
    private SerializerInterface $serializer;

    public function extractMessageFromRequest(Request $request, string $type): object
    {
        $content = $request->getContent();

        if ('' === $content) {
            $content = '{}';
        }

        try {
            $message = $this->serializer->deserialize($content, $type, 'json');
        } catch (\Throwable $e) {
            throw new BadRequestHttpException('Unable to deserialize request content.', $e);
        }

        if (!is_object($message)) {
            throw new BadRequestHttpException('Message cannot be an array.');
        }

        return $message;
    }

    #[Required]
    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }
}
