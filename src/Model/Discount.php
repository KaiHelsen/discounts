<?php
declare(strict_types=1);

namespace App\Model;

use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;

#[Immutable]
class Discount implements IDiscount
{
    //TODO: define the discount class

    private float $discountValue;
    private int $type;


    private const FIXED = 1;
    private const FIXED_DESCRIPTION = 'fixed discount';
    private const VARIABLE = 2;
    private const VARIABLE_DESCRIPTION = 'variable discount';
    private const ONE_FREE = 3;
    private const ONE_FREE_DESCRIPTION = 'one for free discount';


    private function __construct(float $discount, int $type = self::VARIABLE)
    {
        $this->discountValue = $discount;
        $this->type = $type;
    }

    #[Pure] public static function newVariableDiscount(float $percentage): Discount
    {
        return new Discount($percentage, self::VARIABLE);
    }

    #[Pure] public static function newGetOneFreeDiscount(int $count): Discount
    {
        return new Discount($count, self::ONE_FREE);
    }

    #[Pure] public static function newFixedDiscount(float $fixedDiscount): Discount
    {
        return new Discount($fixedDiscount, self::FIXED);
    }

    #[Pure]
    public function calculateDiscountedPrice(float $unitPrice, int $quantity): float
    {
        switch ($this->type)
        {
            case(self::FIXED):
            default:
                return $this->calculateFixedDiscount($unitPrice, $quantity);
            case(self::VARIABLE):
                return $this->calculateVariableDiscount($unitPrice, $quantity);
            case(self::ONE_FREE):
                return $this->calculateOneFreeDiscount($unitPrice, $quantity);
        }
    }

    #[Pure]
    private function calculateFixedDiscount(float $unitPrice, int $quantity): float
    {
        return ($unitPrice * $quantity) - $this->discountValue;
    }

    #[Pure]
    private function calculateVariableDiscount(float $unitPrice, int $quantity): float
    {
        return ($unitPrice * $quantity) * (1-($this->discountValue / 100));
    }

    #[Pure]
    private function calculateOneFreeDiscount(float $unitPrice, int $quantity): float
    {
        $rest = $quantity % (int)($this->discountValue + 1);
        $total = ($quantity - $rest) * ($this->discountValue * $unitPrice / ($this->discountValue + 1));
        $total += $rest * $unitPrice;
        return $total;
    }
}