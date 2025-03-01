<?php

namespace App\Tests\Http;

use App\Http\Range;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @SuppressWarnings("PHPMD.TooManyPublicMethods")
 */
#[CoversClass(Range::class)]
final class RangeTest extends TestCase
{
    public function testDefaultRange(): void
    {
        $range = new Range();

        self::assertEquals(null, $range->limit);
        self::assertEquals(0, $range->offset);
        self::assertEquals(null, $range->size);
        self::assertEquals('items', $range->unit);
        self::assertEquals(['Accept-Ranges' => 'items'], $range->toAcceptRangesHeader());
        self::assertEquals([], $range->toContentRangeHeader());
        self::assertEquals(false, $range->valid());
    }

    public function testBytesRange(): void
    {
        $range = new Range('bytes');

        self::assertEquals(null, $range->limit);
        self::assertEquals(0, $range->offset);
        self::assertEquals(null, $range->size);
        self::assertEquals('bytes', $range->unit);
        self::assertEquals(['Accept-Ranges' => 'bytes'], $range->toAcceptRangesHeader());
        self::assertEquals([], $range->toContentRangeHeader());
        self::assertEquals(false, $range->valid());
    }

    public function testRangeWithOffset(): void
    {
        $range = new Range('items', 10);

        self::assertEquals(null, $range->limit);
        self::assertEquals(10, $range->offset);
        self::assertEquals(null, $range->size);
        self::assertEquals('items', $range->unit);
        self::assertEquals(['Accept-Ranges' => 'items'], $range->toAcceptRangesHeader());
        self::assertEquals([], $range->toContentRangeHeader());
        self::assertEquals(false, $range->valid());
    }

    public function testRangeWithLimitAndOffset(): void
    {
        $range = new Range('items', 10, 20);

        self::assertEquals(20, $range->limit);
        self::assertEquals(10, $range->offset);
        self::assertEquals(null, $range->size);
        self::assertEquals('items', $range->unit);
        self::assertEquals(['Accept-Ranges' => 'items'], $range->toAcceptRangesHeader());
        self::assertEquals(['Content-Range' => 'items 10-29/*'], $range->toContentRangeHeader());
        self::assertEquals(true, $range->valid());
    }

    public function testRangeWithAllParams(): void
    {
        $range = new Range('items', 10, 20, 50);

        self::assertEquals(20, $range->limit);
        self::assertEquals(10, $range->offset);
        self::assertEquals(50, $range->size);
        self::assertEquals('items', $range->unit);
        self::assertEquals(['Accept-Ranges' => 'items'], $range->toAcceptRangesHeader());
        self::assertEquals(['Content-Range' => 'items 10-29/50'], $range->toContentRangeHeader());
        self::assertEquals(true, $range->valid());
    }

    public function testRangeWithOffsetTooLarge(): void
    {
        $range = new Range('items', 60, 20, 50);

        self::assertEquals(20, $range->limit);
        self::assertEquals(60, $range->offset);
        self::assertEquals(50, $range->size);
        self::assertEquals('items', $range->unit);
        self::assertEquals(['Accept-Ranges' => 'items'], $range->toAcceptRangesHeader());
        self::assertEquals(['Content-Range' => 'items */50'], $range->toContentRangeHeader());
        self::assertEquals(false, $range->valid());
    }

    public function testRangeWithNegativeOffset(): void
    {
        $range = new Range('items', -60, 20, 50);

        self::assertEquals(20, $range->limit);
        self::assertEquals(-60, $range->offset);
        self::assertEquals(50, $range->size);
        self::assertEquals('items', $range->unit);
        self::assertEquals(['Accept-Ranges' => 'items'], $range->toAcceptRangesHeader());
        self::assertEquals(['Content-Range' => 'items */50'], $range->toContentRangeHeader());
        self::assertEquals(false, $range->valid());
    }

    public function testRangeWithNegativeLimit(): void
    {
        $range = new Range('items', 10, -20, 50);

        self::assertEquals(-20, $range->limit);
        self::assertEquals(10, $range->offset);
        self::assertEquals(50, $range->size);
        self::assertEquals('items', $range->unit);
        self::assertEquals(['Accept-Ranges' => 'items'], $range->toAcceptRangesHeader());
        self::assertEquals(['Content-Range' => 'items */50'], $range->toContentRangeHeader());
        self::assertEquals(false, $range->valid());
    }

    public function testRangeWithNegativeSize(): void
    {
        $range = new Range('items', 10, 20, -50);

        self::assertEquals(20, $range->limit);
        self::assertEquals(10, $range->offset);
        self::assertEquals(-50, $range->size);
        self::assertEquals('items', $range->unit);
        self::assertEquals(['Accept-Ranges' => 'items'], $range->toAcceptRangesHeader());
        self::assertEquals([], $range->toContentRangeHeader());
        self::assertEquals(false, $range->valid());
    }

    public function testFactoryCreatedRangeWithLimit(): void
    {
        $range = Range::createFromRangeHeader('items=10-29');

        self::assertEquals(20, $range->limit);
        self::assertEquals(10, $range->offset);
        self::assertEquals(null, $range->size);
        self::assertEquals('items', $range->unit);
        self::assertEquals(['Accept-Ranges' => 'items'], $range->toAcceptRangesHeader());
        self::assertEquals(['Content-Range' => 'items 10-29/*'], $range->toContentRangeHeader());
        self::assertEquals(true, $range->valid());
    }

    public function testFactoryCreatedRangeWithoutLimit(): void
    {
        $range = Range::createFromRangeHeader('items=10-');

        self::assertEquals(null, $range->limit);
        self::assertEquals(10, $range->offset);
        self::assertEquals(null, $range->size);
        self::assertEquals('items', $range->unit);
        self::assertEquals(['Accept-Ranges' => 'items'], $range->toAcceptRangesHeader());
        self::assertEquals([], $range->toContentRangeHeader());
        self::assertEquals(false, $range->valid());
    }

    public function testFactoryCreatedInvalidRange(): void
    {
        $this->expectException(BadRequestHttpException::class);
        Range::createFromRangeHeader('items=a-b');
    }
}
