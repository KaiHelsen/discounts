<?php
declare(strict_types=1);

namespace App\Model\Discount;
use App\Model\IDiscountable;

interface IDiscount
{
    public function calculateDiscountedPrice(IDiscountable $item) : float;
}