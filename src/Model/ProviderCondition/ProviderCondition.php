<?php
declare(strict_types=1);

namespace App\Model\ProviderCondition;


use App\Model\Discount\IDiscount;
use App\Model\Order;

abstract class ProviderCondition
{
    protected IDiscount $discount;

    public function __construct(IDiscount $discount)
    {
        $this->discount = $discount;
    }

    abstract public function Evaluate(Order $order): void;
}