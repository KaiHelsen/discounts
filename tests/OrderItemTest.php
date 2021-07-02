<?php

namespace App\Tests;

use App\Model\Discount\Discount;
use App\Model\OrderItem;
use App\Model\Product;
use PHPUnit\Framework\TestCase;

class OrderItemTest extends TestCase
{
    public function testGetters() : void
    {
        $mockProduct = $this->createMock(Product::class);
        $mockProduct->method('getPrice')->willReturn(10.00);
        $item = new OrderItem($mockProduct, 4);
        $item->addDiscount(Discount::newVariableDiscount(10));

        self::assertEquals(40.00, $item->getTotalPrice(), "expecting a total price of 40");
        self::assertEquals(36.00, $item->getDiscountedPrice(), "expecting a total price of 36");
        self::assertEquals(4, $item->getQuantity(), "expecting a quantity of 4");
        self::assertEquals(10.00, $item->getUnitPrice(), "expecting a unit price of 10");
        self::assertEquals($mockProduct, $item->getProduct(), "expecting the product being the same as the generated mock product");
    }

    public function discountProvider(): array
    {
        return [
            [2.00, 1, 2.00, [], 'expected 2.00 with no discounts'],
            [2.00, 1, 1.00, [Discount::newFixedDiscount(1)], 'expected 1.00 with a fixed discount'],
            [2.00, 1, 2.00, [Discount::newGetOneFreeDiscount(4)], 'expected 0.00 with a one free discount'],
            [2.00, 4, 6.00, [Discount::newGetOneFreeDiscount(4)], 'expected 6.00 with a one free discount'],
            [2.00, 4, 7.20, [Discount::newVariableDiscount(10)], 'expected 6.00 with a one free discount'],
        ];
    }

    /**
     * @dataProvider discountProvider
     * @param float $price
     * @param int $quantity
     * @param float $expected
     * @param array $discounts
     * @param string $message
     */
    public function testDiscounts(float $price, int $quantity, float $expected, array $discounts, string $message): void
    {
        $mockProduct = $this->createMock(Product::class);
        $mockProduct->method('getPrice')->willReturn($price);
        $item = new OrderItem($mockProduct, $quantity);

        foreach($discounts as $discount)
        {
            $item->addDiscount($discount);
        }
        self::assertEquals($expected, $item->getDiscountedPrice(), $message);
    }

    public function testPriceComparison(){

        $mockProductA = $this->createMock(Product::class);
        $mockProductA->method('getPrice')->willReturn(20.00);

        $mockProductB = $this->createMock(Product::class);
        $mockProductB->method('getPrice')->willReturn(10.00);

        $mockProductC = $this->createMock(Product::class);
        $mockProductC->method('getPrice')->willReturn(10.00);

        $mockProductD = $this->createMock(Product::class);
        $mockProductD->method('getPrice')->willReturn(1.00);

        $itemNull = null;
        $itemA = new OrderItem($mockProductA, 20);
        $itemB = new OrderItem($mockProductB, 6);
        $itemC = new OrderItem($mockProductC, 10);
        $itemD = new OrderItem($mockProductD, 1);

        self::assertTrue($itemA->isCheaperThan($itemNull), "the item is null, so yes it is considered cheaper");
        self::assertTrue($itemD->isCheaperThan($itemA), "D is cheaper than A");
        self::assertFalse($itemC->isCheaperThan($itemB), "their prices are the same, so it is not cheaper");
        self::assertFalse($itemD->isCheaperThan($itemD), "they are the same object, so no, it is not cheaper");

        self::assertFalse($itemA->isCheaperThan($itemB), "A is more expensive than B");
    }
}
