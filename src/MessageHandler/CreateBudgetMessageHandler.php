<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Budget;
use App\Message\CreateBudgetMessage;
use App\Model\Response\Assembler\BudgetDtoAssembler;
use App\Model\Response\BudgetDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateBudgetMessageHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BudgetDtoAssembler $assembler,
    ) {
    }

    public function __invoke(CreateBudgetMessage $message): BudgetDto
    {
        $budget = new Budget($message->dateEnd, $message->name, $message->dateStart);
        $this->entityManager->persist($budget);
        $this->entityManager->flush();

        return $this->assembler->create($budget);
    }
}
