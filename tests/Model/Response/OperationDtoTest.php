<?php

namespace App\Tests\Model\Response;

use App\Model\Response\HalDto;
use App\Model\Response\OperationDto;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

#[CoversClass(OperationDto::class)]
#[UsesClass(HalDto::class)]
final class OperationDtoTest extends TestCase
{
    public function testSimpleSerialization(): void
    {
        $accountUuid = Uuid::v4();
        $date = new DateTimeImmutable('2004-02-12T15:19:21+00:00');
        $operationUuid = Uuid::v4();
        $expected = <<<ETX
{"_links":{"account":{"href":"\/accounts\/$accountUuid"},"self":{"href":"\/operations\/$operationUuid"}},
"amount":"100.00","category":"","date":"2004-02-12T15:19:21+00:00","name":"Operation","party":"","tags":[]}
ETX;
        $actual = json_encode(
            new OperationDto($accountUuid, '100.00', '', $date, $operationUuid, 'Operation', '', [])
        ) ?: '';
        self::assertJsonStringEqualsJsonString($expected, $actual);
    }
}
