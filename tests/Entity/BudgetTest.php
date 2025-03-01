<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Account;
use App\Entity\Budget;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Budget::class)]
#[UsesClass(Account::class)]
class BudgetTest extends TestCase
{
    public function testAddAndRemoveAccount(): void
    {
        $budget = new Budget(new DateTimeImmutable(), 'Budget', new DateTimeImmutable());
        self::assertEquals(0, $budget->accounts->count());
        $account = new Account($budget, 'Account');
        $budget->addAccount($account);
        self::assertEquals(1, $budget->accounts->count());
        self::assertTrue($budget->accounts->contains($account));
        $budget->removeAccount($account);
        self::assertEquals(0, $budget->accounts->count());
    }

    public function testStringable(): void
    {
        $budget = new Budget(new DateTimeImmutable(), 'Budget', new DateTimeImmutable());
        self::assertEquals('Budget', '' . $budget);
    }
}
