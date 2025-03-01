<?php

namespace App\Tests\Model\Response\Assembler;

use App\Entity\Account;
use App\Entity\Budget;
use App\Model\Response\AccountDto;
use App\Model\Response\Assembler\AccountDtoAssembler;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

#[CoversClass(AccountDtoAssembler::class)]
#[UsesClass(Account::class)]
#[UsesClass(Budget::class)]
#[UsesClass(AccountDto::class)]
final class AccountDtoAssemblerTest extends TestCase
{
    public function testException(): void
    {
        $budget = new Budget(new DateTimeImmutable(), 'Budget', new DateTimeImmutable());
        $account = new Account($budget, 'Account');

        $this->expectException(InvalidArgumentException::class);
        new AccountDtoAssembler()->create($account);
    }

    public function testSimpleCase(): void
    {
        $budget = new Budget(new DateTimeImmutable(), 'Budget', new DateTimeImmutable());
        $budgetUuid = Uuid::v4();
        $budget->uuid = $budgetUuid;

        $account = new Account($budget, 'Account');
        $accountUuid = Uuid::v4();
        $account->uuid = $accountUuid;

        $expected = new AccountDto($budgetUuid, $accountUuid, false, 'Account');
        $actual = (new AccountDtoAssembler())->create($account);
        self::assertEquals($expected, $actual);
    }
}
