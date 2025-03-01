<?php

namespace App\Controller;

use App\Http\PaginatedJsonResponse;
use App\Http\Range;
use App\Message\IdInterface;
use App\Message\PaginableInterface;
use App\MessageHandler\PaginationDecorator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Uid\Uuid;

class ListController extends AbstractController implements MessageExtractorInterface, MessageValidatorInterface
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
        $range = null;

        if (isset($id) && $message instanceof IdInterface) {
            $message->id = $id;
        }

        if (
            $request->headers->has('Range')
            && null !== $request->headers->get('Range')
            && $message instanceof PaginableInterface
        ) {
            $range = Range::createFromRangeHeader($request->headers->get('Range'));
            $message->limit = $range->limit;
            $message->offset = $range->offset;
        }

        $this->validateMessage($message);

        $dto = $this->handle($message);

        if ($dto instanceof PaginationDecorator) {
            if ($range instanceof Range) {
                return new PaginatedJsonResponse($dto, $range->unit, $range->offset);
            }

            return new PaginatedJsonResponse($dto);
        }

        return $this->json($dto);
    }
}
