<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Account;
use App\Entity\Budget;
use App\Entity\Operation;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Account::class)]
#[UsesClass(Budget::class)]
#[UsesClass(Operation::class)]
class AccountTest extends TestCase
{
    public function testAddAndRemoveOperation(): void
    {
        $account = new Account(new Budget(new DateTimeImmutable(), 'Budget', new DateTimeImmutable()), 'Account');
        self::assertEquals(0, $account->operations->count());
        $operation = new Operation($account, '100.00', new DateTimeImmutable(), 'Operation');
        $account->addOperation($operation);
        self::assertEquals(1, $account->operations->count());
        self::assertTrue($account->operations->contains($operation));
        $account->removeOperation($operation);
        self::assertEquals(0, $account->operations->count());
    }

    public function testStringable(): void
    {
        $account = new Account(new Budget(new DateTimeImmutable(), 'Budget', new DateTimeImmutable()), 'Account');
        self::assertEquals('Account', '' . $account);
    }
}
