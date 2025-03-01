<?php

namespace App\Tests\Model\Response\Assembler;

use App\Entity\Account;
use App\Entity\Budget;
use App\Entity\Category;
use App\Entity\Operation;
use App\Entity\Party;
use App\Entity\Tag;
use App\Entity\Taxon;
use App\Model\Response\Assembler\OperationDtoAssembler;
use App\Model\Response\OperationDto;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

#[CoversClass(OperationDtoAssembler::class)]
#[UsesClass(Account::class)]
#[UsesClass(Budget::class)]
#[UsesClass(Category::class)]
#[UsesClass(Operation::class)]
#[UsesClass(OperationDto::class)]
#[UsesClass(Party::class)]
#[UsesClass(Tag::class)]
#[UsesClass(Taxon::class)]
final class OperationDtoAssemblerTest extends TestCase
{
    public function testComplexCase(): void
    {
        $budget = new Budget(new DateTimeImmutable(), 'Budget', new DateTimeImmutable());

        $account = new Account($budget, 'Account');
        $accountUuid = Uuid::v4();
        $account->uuid = $accountUuid;

        $date = new DateTimeImmutable();

        $operation = new Operation($account, '100.00', $date, 'Operation');
        $operationUuid = Uuid::v4();
        $operation->uuid = $operationUuid;
        $operation->addTag(new Tag('A'));
        $operation->addTag(new Tag('B'));
        $operation->addTag(new Tag('C'));
        $operation->category = new Category('D');
        $operation->party = new Party('E');

        $expected = new OperationDto(
            $accountUuid,
            '100.00',
            'D',
            $date,
            $operationUuid,
            'Operation',
            'E',
            ['A', 'B', 'C']
        );
        $actual = (new OperationDtoAssembler())->create($operation);
        self::assertEquals($expected, $actual);
    }

    public function testException(): void
    {
        $account = new Account(new Budget(new DateTimeImmutable(), 'Budget', new DateTimeImmutable()), 'Account');
        $operation = new Operation($account, '100.00', new DateTimeImmutable(), 'Operation');

        $this->expectException(InvalidArgumentException::class);
        new OperationDtoAssembler()->create($operation);
    }

    public function testSimpleCase(): void
    {
        $budget = new Budget(new DateTimeImmutable(), 'Budget', new DateTimeImmutable());

        $account = new Account($budget, 'Account');
        $accountUuid = Uuid::v4();
        $account->uuid = $accountUuid;

        $date = new DateTimeImmutable();

        $operation = new Operation($account, '100.00', $date, 'Operation');
        $operationUuid = Uuid::v4();
        $operation->uuid = $operationUuid;

        $expected = new OperationDto($accountUuid, '100.00', '', $date, $operationUuid, 'Operation', '', []);
        $actual = (new OperationDtoAssembler())->create($operation);
        self::assertEquals($expected, $actual);
    }
}
