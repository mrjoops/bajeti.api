<?php

namespace App\Tests\Model\Response\Assembler;

use App\Entity\Account;
use App\Entity\Budget;
use App\Model\Response\AccountDto;
use App\Model\Response\Assembler\AccountDtoAssembler;
use App\Model\Response\Assembler\BudgetDtoAssembler;
use App\Model\Response\BudgetDto;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

#[CoversClass(BudgetDtoAssembler::class)]
#[UsesClass(Account::class)]
#[UsesClass(AccountDtoAssembler::class)]
#[UsesClass(Budget::class)]
#[UsesClass(AccountDto::class)]
#[UsesClass(BudgetDto::class)]
final class BudgetDtoAssemblerTest extends TestCase
{
    public function testComplexCase(): void
    {
        $dateEnd = new DateTimeImmutable();
        $dateStart = new DateTimeImmutable();

        $budget = new Budget($dateEnd, 'Budget', $dateStart);
        $budgetUuid = Uuid::v4();
        $budget->uuid = $budgetUuid;

        $accountUuid1 = Uuid::v4();
        $account1 = new Account($budget, 'Account 1');
        $account1->uuid = $accountUuid1;
        $accountDto1 = new AccountDto($budgetUuid, $accountUuid1, false, 'Account 1');
        $budget->addAccount($account1);

        $accountUuid2 = Uuid::v4();
        $account2 = new Account($budget, 'Account 2');
        $account2->uuid = $accountUuid2;
        $accountDto2 = new AccountDto($budgetUuid, $accountUuid2, false, 'Account 2');
        $budget->addAccount($account2);

        $accountUuid3 = Uuid::v4();
        $account3 = new Account($budget, 'Account 3');
        $account3->uuid = $accountUuid3;
        $accountDto3 = new AccountDto($budgetUuid, $accountUuid3, false, 'Account 3');
        $budget->addAccount($account3);

        $expected = new BudgetDto(
            [$accountDto1, $accountDto2, $accountDto3],
            $dateEnd,
            $dateStart,
            $budgetUuid,
            'Budget'
        );
        $actual = (new BudgetDtoAssembler())->create($budget);
        self::assertEquals($expected, $actual);
    }

    public function testException(): void
    {
        $budget = new Budget(new DateTimeImmutable(), 'Budget', new DateTimeImmutable());

        $this->expectException(InvalidArgumentException::class);
        new BudgetDtoAssembler()->create($budget);
    }

    public function testSimpleCase(): void
    {
        $dateEnd = new DateTimeImmutable();
        $dateStart = new DateTimeImmutable();

        $budget = new Budget($dateEnd, 'Budget', $dateStart);
        $budgetUuid = Uuid::v4();
        $budget->uuid = $budgetUuid;

        $expected = new BudgetDto([], $dateEnd, $dateStart, $budgetUuid, 'Budget');
        $actual = (new BudgetDtoAssembler())->create($budget);
        self::assertEquals($expected, $actual);
    }
}
