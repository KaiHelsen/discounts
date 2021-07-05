<?php
declare(strict_types=1);

namespace App\Model\ProviderCondition;


use App\Model\Discount\Discount;
use App\Model\Order;

abstract class ProviderCondition
{
    protected Discount $discount;

    public function __construct(Discount $discount)
    {
        $this->discount = $discount;
    }

    abstract public function Evaluate(Order $order): bool;
}