<?php
declare(strict_types=1);

namespace App\Model;


use JetBrains\PhpStorm\Pure;

class DiscountCalculator
{

    public const REVENUE_DISCOUNT_VALUE = 1000;
    public const TOOLS = 1;
    public const SWITCHES = 2;

    public function __construct()
    {
    }

    public function calculateDiscounts(Order $order): void
    {
        $customer = $order->getCustomer();
        if ($customer->getRevenue() > self::REVENUE_DISCOUNT_VALUE)
        {
            //TODO: add 10% discount to whole order
        }

        $items = $order->getItems();
        $cheapestProduct = null;
        $toolCount = 0;
        foreach ($items as $item)
        {
            if ($item->getProduct()->getCategory() === self::SWITCHES)
            {
                //TODO: add discount that makes 1 out of every 6 items free
            }
            elseif ($item->getProduct()->getCategory() === self::TOOLS)
            {
                $toolCount++;
                $cheapestProduct = Product::findCheapestProduct($cheapestProduct, $item->getProduct());
            }
        }

        if ($toolCount >= 2)
        {
            //TODO: add 20% discount on cheapest product
        }
    }
}