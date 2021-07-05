<?php


namespace App\Model\ProviderCondition;


use App\Model\Discount\Discount;
use App\Model\Order;
use JetBrains\PhpStorm\Pure;

class ClientRevenueCondition extends ProviderCondition
{

    private int $minRevenue;

    public function __construct(Discount $discount, float $minRevenue)
    {
        parent::__construct($discount);
        $this->minRevenue = $minRevenue;
    }

    public function Evaluate(Order $order): bool
    {
        $customer = $order->getCustomer();
        if ($customer->getRevenue() > $this->minRevenue)
        {
            $order->addDiscount($this->discount);
            return true;
        }

        return false;
    }
}