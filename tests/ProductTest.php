<?php

namespace App\Tests;

use App\Model\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{

    public function testGetters(): void
    {
        $product1 = new Product(1, "foo", 1, 20.00);
        $product2 = new Product(6, "bar", 2, 15.00);

        self::assertEquals(1, $product1->getId(), "expected 1");
        self::assertEquals("foo", $product1->getDescription(), "expected foo");
        self::assertEquals(1, $product1->getCategory(), "expected 1");
        self::assertEquals(20, $product1->getPrice(), "expected 20.00");

        self::assertEquals(6, $product2->getId(), "expected 6");
        self::assertEquals("bar", $product2->getDescription(), "expected bar");
        self::assertEquals(2, $product2->getCategory(), "expected 2");
        self::assertEquals(15, $product2->getPrice(), "expected 15.00");
    }
}
