<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Account;
use App\Message\FetchAccountMessage;
use App\Model\Response\AccountDto;
use App\Model\Response\Assembler\AccountDtoAssembler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class FetchAccountMessageHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AccountDtoAssembler $assembler,
    ) {
    }

    public function __invoke(FetchAccountMessage $message): AccountDto
    {
        $account = $this->entityManager->getRepository(Account::class)->findOneBy(['uuid' => $message->id]);

        if (!$account instanceof Account) {
            throw new NotFoundHttpException();
        }

        return $this->assembler->create($account);
    }
}
