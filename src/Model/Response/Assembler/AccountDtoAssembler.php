<?php

declare(strict_types=1);

namespace App\Model\Response\Assembler;

use App\Entity\Account;
use App\Model\Response\AccountDto;
use InvalidArgumentException;

class AccountDtoAssembler
{
    public function create(Account $account): AccountDto
    {
        if (is_null($account->uuid) || is_null($account->budget->uuid)) {
            throw new InvalidArgumentException();
        }

        return new AccountDto(
            $account->budget->uuid,
            $account->uuid,
            false,
            $account->name,
        );
    }
}
