<?php

namespace App\Tests;

use App\Model\Discount\Discount;
use App\Model\OrderItem;
use App\Model\Product;
use PHPUnit\Framework\TestCase;

class DiscountTest extends TestCase
{
    public function fixedDiscountProvider() : array
    {
        return [
            [10, 10, 10, 10, "expected 10"],
            [5, 10, 2, 5, "expected 5"],
        ];
    }

    /**
     * @dataProvider fixedDiscountProvider
     * @param float $discountValue
     * @param float $unitPrice
     * @param int $quantity
     * @param float $expected
     * @param string $message
     */
    public function testFixedDiscount(float $discountValue, float $unitPrice, int $quantity, float $expected, string $message): void
    {
        $discount = Discount::newFixedDiscount($discountValue);

        $this->runMockDiscountableTest($quantity, $unitPrice, $expected, $discount, $message);
    }

    public function variableDiscountProvider() : array
    {
        return [
            [10, 10, 10, 10, "expected 10"],
            [5, 10, 2, 1, "expected 1"],
        ];
    }

    /**
     * @dataProvider variableDiscountProvider
     * @param float $discountValue
     * @param float $unitPrice
     * @param int $quantity
     * @param float $expected
     * @param string $message
     */
    public function testVariableDiscount(float $discountValue, float $unitPrice, int $quantity, float $expected, string $message): void
    {
        $discountValue = Discount::newVariableDiscount($discountValue);

        $this->runMockDiscountableTest($quantity, $unitPrice, $expected, $discountValue, $message);
    }

    public function oneFreeDiscountProvider() : array
    {
        return [
            [5, 10, 6, 10, "expected 10"],
            [5, 10, 7, 10, "expected 10"],
            [4, 1, 23, 5, "expected 5"],
        ];
    }

    /**
     * @dataProvider oneFreeDiscountProvider
     * @param float $discountValue
     * @param float $unitPrice
     * @param int $quantity
     * @param float $expected
     * @param string $message
     */
    public function testOneFreeDiscount(float $discountValue, float $unitPrice, int $quantity, float $expected, string $message): void
    {
        $discount = Discount::newGetOneFreeDiscount($discountValue);

        $this->runMockDiscountableTest($quantity, $unitPrice, $expected, $discount, $message);
    }


    private function runMockDiscountableTest(int $quantity, float $unitPrice, float $expected, Discount $discount, string $message): void
    {
        $mockObject = $this->createMock(OrderItem::class);
        $mockObject->method('getQuantity')->willReturn($quantity);
        $mockObject->method('getUnitPrice')->willReturn($unitPrice);

        self::assertEquals($expected, $discount->calculateDiscountedPrice($mockObject), $message);
    }

    public function testComparisons():void
    {
        $A = Discount::newVariableDiscount(10);

        $B = Discount::newVariableDiscount(10);
        $C = Discount::newVariableDiscount(25);
        $D = Discount::newGetOneFreeDiscount(10);
        $E = Discount::newFixedDiscount(25);
        $F = Discount::newGetOneFreeDiscount(10);

        self::assertTrue($A->equals($B), 'expected true');
        self::assertTrue($D->equals($F), 'expected true');

        self::assertFalse($A->equals($C), 'expected false');
        self::assertFalse($A->equals($D), 'expected false');
        self::assertFalse($A->equals($E), 'expected false');
        self::assertFalse($D->equals($E), 'expected false');
    }
}
