<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Budget;
use App\Message\FetchBudgetMessage;
use App\Model\Response\Assembler\BudgetDtoAssembler;
use App\Model\Response\BudgetDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class FetchBudgetMessageHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BudgetDtoAssembler $assembler,
    ) {
    }

    public function __invoke(FetchBudgetMessage $message): BudgetDto
    {
        $budget = $this->entityManager->getRepository(Budget::class)->findOneBy(['uuid' => $message->id]);

        if (!$budget instanceof Budget) {
            throw new NotFoundHttpException();
        }

        return $this->assembler->create($budget);
    }
}
