<?php

namespace App\Tests\Model\Response;

use App\Model\Response\AccountDto;
use App\Model\Response\HalDto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

#[CoversClass(AccountDto::class)]
#[UsesClass(HalDto::class)]
final class AccountDtoTest extends TestCase
{
    public function testSerialization(): void
    {
        $accountUuid = Uuid::v4();
        $budgetUuid = Uuid::v4();
        $expected = <<<ETX
{"_links":{"budget":{"href":"\/budgets\/$budgetUuid"},"operations":{"href":"\/accounts\/$accountUuid\/operations"},
"self":{"href":"\/accounts\/$accountUuid"}},"name":"Account","personal":false}
ETX;
        $actual = json_encode(new AccountDto($budgetUuid, $accountUuid, false, 'Account')) ?: '';
        self::assertJsonStringEqualsJsonString($expected, $actual);
    }
}
