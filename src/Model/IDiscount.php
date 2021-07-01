<?php
declare(strict_types=1);

namespace App\Model;


interface IDiscount
{
    public function calculateDiscountedPrice(float $unitPrice, int $quantity) : float;
}