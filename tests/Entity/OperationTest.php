<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Account;
use App\Entity\Budget;
use App\Entity\Operation;
use App\Entity\Tag;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Operation::class)]
#[UsesClass(Account::class)]
#[UsesClass(Budget::class)]
#[UsesClass(Tag::class)]
class OperationTest extends TestCase
{
    public function testAddAndRemoveAccount(): void
    {
        $account = new Account(new Budget(new DateTimeImmutable(), 'Budget', new DateTimeImmutable()), 'Account');
        $operation = new Operation($account, '100.00', new DateTimeImmutable(), 'Operation');
        self::assertEquals(0, $operation->tags->count());
        $tag = new Tag('Tag');
        $operation->addTag($tag);
        self::assertEquals(1, $operation->tags->count());
        self::assertTrue($operation->tags->contains($tag));
        $operation->removeTag($tag);
        self::assertEquals(0, $operation->tags->count());
    }

    public function testStringable(): void
    {
        $account = new Account(new Budget(new DateTimeImmutable(), 'Budget', new DateTimeImmutable()), 'Account');
        $operation = new Operation($account, '100.00', new DateTimeImmutable(), 'Operation');
        self::assertEquals('Operation', '' . $operation);
    }
}
