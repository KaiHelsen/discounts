<?php
declare(strict_types=1);

namespace App\Model;


use App\Model\Discount\Discount;
use JetBrains\PhpStorm\Pure;

class DiscountProvider
{

    public const REVENUE_DISCOUNT_VALUE = 1000;
    public const TOOLS = 1;
    public const SWITCHES = 2;

    public function __construct()
    {
        //TODO: I'm sure there's stuff to do here
    }

    public function calculateDiscounts(Order $order): void
    {
        $this->giveCustomerDiscounts($order);

        $this->giveOrderItemDiscounts($order);
    }

    public function giveCustomerDiscounts(Order $order): void
    {

    }

    public function giveOrderItemDiscounts(Order $order): void
    {

    }
}