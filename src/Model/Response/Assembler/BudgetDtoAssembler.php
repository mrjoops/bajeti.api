<?php

declare(strict_types=1);

namespace App\Model\Response\Assembler;

use App\Entity\Account;
use App\Entity\Budget;
use App\Model\Response\Assembler\AccountDtoAssembler;
use App\Model\Response\BudgetDto;
use App\Model\Response\BudgetListDto;
use InvalidArgumentException;

class BudgetDtoAssembler
{
    public function create(Budget $budget): BudgetDto
    {
        if (is_null($budget->uuid)) {
            throw new InvalidArgumentException();
        }

        return new BudgetDto(
            $budget->accounts->map(function (Account $account) {
                $assembler = new AccountDtoAssembler();
                return $assembler->create($account);
            })->toArray(),
            $budget->endsAt,
            $budget->startsAt,
            $budget->uuid,
            $budget->name,
        );
    }

    /**
     * @param array<Budget> $budgets
     */
    public function createList(array $budgets): BudgetListDto
    {
        return new BudgetListDto(array_map(fn (Budget $budget) => $this->create($budget), $budgets));
    }
}
