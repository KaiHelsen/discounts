<?php
declare(strict_types=1);

namespace App\Model;


interface IDiscountable
{
    public function applyDiscounts() : void;
    public function getDiscountedPrice() : float;

    public function getUnitPrice() : float;
    public function getQuantity() : int;
}