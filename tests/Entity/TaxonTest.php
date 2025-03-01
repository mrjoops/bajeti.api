<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Taxon;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Taxon::class)]
class TaxonTest extends TestCase
{
    public function testStringable(): void
    {
        $taxon = new Taxon('Taxon');
        self::assertEquals('Taxon', '' . $taxon);
    }
}
