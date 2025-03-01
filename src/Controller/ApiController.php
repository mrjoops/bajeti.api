<?php

declare(strict_types=1);

namespace App\Controller;

use App\Message\IdInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Uid\Uuid;

class ApiController extends AbstractController implements MessageExtractorInterface, MessageValidatorInterface
{
    use HandleTrait;
    use MessageExtractorTrait;
    use MessageValidatorTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(Request $request, string $type, ?Uuid $id): Response
    {
        $message = $this->extractMessageFromRequest($request, $type);

        if (isset($id) && $message instanceof IdInterface) {
            $message->id = $id;
        }

        $this->validateMessage($message);

        $dto = $this->handle($message);

        if (is_null($dto)) {
            return new Response(
                '',
                $request->isMethod(Request::METHOD_DELETE) ? Response::HTTP_RESET_CONTENT : Response::HTTP_NO_CONTENT
            );
        }

        return $this->json($dto, $request->isMethod(Request::METHOD_POST) ? Response::HTTP_CREATED : Response::HTTP_OK);
    }
}
