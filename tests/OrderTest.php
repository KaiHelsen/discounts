<?php

namespace App\Tests;

use App\Model\Customer;
use App\Model\Discount\Discount;
use App\Model\Order;
use App\Model\OrderItem;
use App\Model\Product;
use DateTime;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    public function testGetters(): void
    {
        $customer = new Customer(1, 'foo', new DateTime(), 200.00);
        $items = [
            new OrderItem(new Product(1, "foo", 1, 2.00), 1, 2.00),
            new OrderItem(new Product(1, "bar", 1, 4), 3),
            new OrderItem(new Product(1, "blip", 1, 6), 2),
            new OrderItem(new Product(1, "blep", 1, 12), 4),
        ];
        $order = new Order(1, $customer, $items);

        self::assertEquals($customer, $order->getCustomer(), "expecting the same customer");
        self::assertEquals(1, $order->getId(), "should be 1");
        self::assertEquals(2, $order->getItems()[0]->getUnitPrice(), "expected 74");
        self::assertEquals(2 + 12 + 12 + 48, $order->getTotalPrice(), "expected 74");
        self::assertEquals(74, $order->getDiscountedPrice(), "expected 74");
        self::assertIsArray($order->getItems(), "expected an array of orderItems");

        self::assertIsArray($order->getDiscounts(), "expected empty array");
        self::assertEquals([], $order->getDiscounts(), "expected empty array");

        self::assertEquals(1, $order->getQuantity(), "expected 1, and only ever 1");
        self::assertEquals(74, $order->getUnitPrice(), "should be the same as the discounted price");
    }

    public function testDiscountArrayFunctionality(): void
    {
        $customer = new Customer(1, 'foo', new DateTime(), 200.00);
        $items = [];
        $order = new Order(1, $customer, $items);

        self::assertEquals([], $order->getDiscounts(), "expected Empty array");

        $discount = Discount::newFixedDiscount(20);
        $order->addDiscount($discount);

        self::assertEquals([$discount], $order->getDiscounts(), "expected array with one discount");

        $order->removeDiscount($discount);

        self::assertEquals([], $order->getDiscounts(), "expected an emptied array");
    }
}
