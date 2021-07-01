<?php

namespace App\Tests;

use App\Model\Discount;
use PHPUnit\Framework\TestCase;

class DiscountTest extends TestCase
{
    public function testSomething(): void
    {
        self::assertTrue(true);
    }

    public function fixedDiscountProvider() : array
    {
        return [
            [10, 10, 10, 90, "expected 90"],
        ];
    }

    /**
     * @dataProvider fixedDiscountProvider
     * @param float $discount
     * @param float $price
     * @param int $quantity
     * @param float $expected
     * @param string $message
     */
    public function testFixedDiscount(float $discount, float $price, int $quantity, float $expected, string $message): void
    {
        $discount = Discount::newFixedDiscount($discount);
        self::assertEquals($expected,$discount->calculateDiscountedPrice($price, $quantity),$message);
    }

    public function variableDiscountProvider() : array
    {
        return [
            [10, 10, 10, 90, "expected 90"],
        ];
    }

    /**
     * @dataProvider variableDiscountProvider
     * @param float $discount
     * @param float $price
     * @param int $quantity
     * @param float $expected
     * @param string $message
     */
    public function testVariableDiscount(float $discount, float $price, int $quantity, float $expected, string $message): void
    {
        $discount = Discount::newVariableDiscount($discount);
        self::assertEquals($expected,$discount->calculateDiscountedPrice($price, $quantity),$message);
    }

    public function oneFreeDiscountProvider() : array
    {
        return [
            [5, 10, 6, 50, "expected 50"],
            [5, 10, 7, 60, "expected 50"],
        ];
    }

    /**
     * @dataProvider oneFreeDiscountProvider
     * @param float $amount
     * @param float $price
     * @param int $quantity
     * @param float $expected
     * @param string $message
     */
    public function testOneFreeDiscount(float $amount, float $price, int $quantity, float $expected, string $message): void
    {
        $discount = Discount::newGetOneFreeDiscount($amount);
        self::assertEquals($expected,$discount->calculateDiscountedPrice($price, $quantity),$message);
    }
}
