<?php

namespace App\Tests;

use App\Model\Customer;
use App\Model\Discount\Discount;
use App\Model\Order;
use App\Model\OrderItem;
use App\Model\Product;
use App\Model\ProviderCondition\CategoryCondition;
use App\Model\ProviderCondition\CheapestInCategoryCondition;
use App\Model\ProviderCondition\ClientRevenueCondition;
use PHPUnit\Framework\TestCase;

class ProviderConditionTest extends TestCase
{
    public function RevenueProvider(): array
    {
        return [
            [1000.00, 1001.00, true, "expected true"],
            [1000.00, 650.00, false, "expected false"],
            [1000.00, 1000.00, false, "expected false"],
        ];
    }

    /**
     * @dataProvider RevenueProvider
     * @param float $revenue
     * @param float $minRevenue
     * @param bool $expected
     * @param string $msg
     */
    public function testClientRevenue(float $minRevenue, float $revenue, bool $expected, string $msg): void
    {
        $discount = $this->createMock(Discount::class);
        $order = $this->createMock(Order::class);
        $customer = $this->createMock(Customer::class);
        $order->method('getCustomer')->wilLReturn($customer);
        $customer->method('getRevenue')->willReturn($revenue);

        $condition = new ClientRevenueCondition($discount, $minRevenue);
        self::assertEquals($expected, $condition->Evaluate($order), $msg);
    }

    public function cheapestItemProvider(): array
    {
        $orders = $this->generateOrderItems();
        return [
            [[], 2, [], false, "expected false"],
            [$orders, 2, [], true, "expected true"],
            [$orders, 7, [], false, "expected false"],
            [$orders, 2, [1], true, "expected true"],
            [$orders, 2, [1, 3], true, "expected true"],
        ];
    }

    /**
     * @dataProvider cheapestItemProvider
     * @param OrderItem[] $orders
     * @param int $minItems
     * @param array $validCategories
     * @param bool $expected
     * @param string $msg
     */
    public function testCheapestInCategory(array $orders, int $minItems, array $validCategories, bool $expected, string $msg): void
    {
        $discount = $this->createMock(Discount::class);
        $order = $this->createMock(Order::class);
        $order->method('getItems')->willReturn($orders);

        $condition = new CheapestInCategoryCondition($discount, $minItems, $validCategories);

        self::assertEquals($expected, $condition->Evaluate($order), $msg);
    }

    public function categoryTestProvider(): array
    {
        $orders = $this->generateOrderItems();

        return [
            [$orders, [], false, "expecting no discounts"],
            [$orders, [1], true, "expecting discounts!"],
        ];
    }

    /**
     * @dataProvider categoryTestProvider
     * @param array $orders
     * @param array $validCategories
     * @param bool $expected
     * @param string $msg
     */
    public function testCategoryCondition(array $orders, array $validCategories, bool $expected, string $msg): void
    {
        $discount = $this->createMock(Discount::class);
        $order = $this->createMock(Order::class);
        $order->method('getItems')->willReturn($orders);

        $condition = new CategoryCondition($discount, $validCategories);
        self::assertEquals($expected, $condition->Evaluate($order), $msg);
    }

    private function generateOrderItems(): array
    {
        return [
            new OrderItem(new Product(1, 'foo', 1, 20.00), 2),
            new OrderItem(new Product(2, 'bar', 1, 10.00), 2),
            new OrderItem(new Product(3, 'sna', 2, 30.00), 2),
            new OrderItem(new Product(4, 'fu', 3, 40.00), 2),
            new OrderItem(new Product(5, 'ayy', 2, 20.00), 2),
            new OrderItem(new Product(6, 'wol', 3, 10.00), 2),
        ];
    }
}
