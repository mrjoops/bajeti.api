<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Category;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Category::class)]
class CategoryTest extends TestCase
{
    public function testUuidGenerator(): void
    {
        $category = new Category('Category');
        self::assertFalse(isset($category->uuid));
        $category->generateUuid();
        self::assertNotNull($category->uuid);
    }

    public function testStringable(): void
    {
        $category = new Category('Category');
        self::assertEquals('Category', '' . $category);
    }
}
