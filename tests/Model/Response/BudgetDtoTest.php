<?php

namespace App\Tests\Model\Response;

use App\Model\Response\BudgetDto;
use App\Model\Response\HalDto;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

#[CoversClass(BudgetDto::class)]
#[UsesClass(HalDto::class)]
final class BudgetDtoTest extends TestCase
{
    public function testSimpleSerialization(): void
    {
        $dateEnd = new DateTimeImmutable('2004-02-12T15:19:21+00:00');
        $dateStart = new DateTimeImmutable('2002-01-23T17:39:29+00:00');
        $budgetUuid = Uuid::v4();
        $expected = <<<ETX
{"_links":{"self":{"href":"\/budgets\/$budgetUuid"}},"dateEnd":"2004-02-12","dateStart":"2002-01-23","name":"Budget"}
ETX;
        $actual = json_encode(new BudgetDto([], $dateEnd, $dateStart, $budgetUuid, 'Budget')) ?: '';
        self::assertJsonStringEqualsJsonString($expected, $actual);
    }
}
