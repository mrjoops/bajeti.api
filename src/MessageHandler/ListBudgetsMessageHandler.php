<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Budget;
use App\Message\ListBudgetsMessage;
use App\MessageHandler\PaginationDecorator;
use App\Model\Response\Assembler\BudgetDtoAssembler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ListBudgetsMessageHandler implements OrderByCreatorInterface
{
    use OrderByCreatorTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private BudgetDtoAssembler $assembler,
    ) {
    }

    public function __invoke(ListBudgetsMessage $message): PaginationDecorator
    {
        $repository = $this->entityManager->getRepository(Budget::class);
        $orderBy = $this->createOrderByFromMessage($message);

        if (is_null($orderBy)) {
            $orderBy = [];
        }

        $results = $repository->findBy([], $orderBy, $message->limit, $message->offset);

        return new PaginationDecorator($this->assembler->createList($results), count($results), $repository->count());
    }
}
