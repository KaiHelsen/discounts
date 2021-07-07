<?php
declare(strict_types=1);

namespace App\Model\Discount;

use App\Model\IDiscountable;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;

#[Immutable]
class Discount
{
    private float $discountValue;
    private int $type;
    private string $description;

    private const FIXED = 1;
    private const FIXED_DESCRIPTION = 'fixed discount';
    private const VARIABLE = 2;
    private const VARIABLE_DESCRIPTION = 'variable discount';
    private const ONE_FREE = 3;
    private const ONE_FREE_DESCRIPTION = 'one for free discount';


    private function __construct(float $discount, string $description = "", int $type = self::VARIABLE)
    {
        $this->discountValue = $discount;
        $this->type = $type;
    }

    #[Pure] public static function newVariableDiscount(float $percentage, string $description = ""): Discount
    {
        return new Discount($percentage, $description = "", self::VARIABLE);
    }

    #[Pure] public static function newGetOneFreeDiscount(int $oneFor, string $description = ""): Discount
    {
        return new Discount($oneFor, $description = "",self::ONE_FREE);
    }

    #[Pure] public static function newFixedDiscount(float $fixedDiscount, string $description = ""): Discount
    {
        return new Discount($fixedDiscount, $description = "", self::FIXED);
    }

    /**
     * @return float
     */
    #[Pure]
    public function getDiscountValue(): float
    {
        return $this->discountValue;
    }

    /**
     * @return int
     */
    #[Pure]
    public function getType(): int
    {
        return $this->type;
    }

    #[Pure]
    public function calculateDiscountedPrice(IDiscountable $item): float
    {
        return match ($this->type)
        {
            default => $this->calculateFixedDiscount(),
            self::VARIABLE => $this->calculateVariableDiscount($item->getUnitPrice() * $item->getQuantity()),
            self::ONE_FREE => $this->calculateOneFreeDiscount($item->getUnitPrice(), $item->getQuantity()),
        };
    }

    #[Pure]
    private function calculateFixedDiscount(): float
    {
        return $this->discountValue;
    }

    #[Pure]
    private function calculateVariableDiscount(float $totalPrice): float
    {
        return ($totalPrice) * ($this->discountValue / 100);
    }

    #[Pure]
    private function calculateOneFreeDiscount(float $unitPrice, int $quantity): float
    {
        $rest = $quantity % (int)($this->discountValue);
        $freeItems = ($quantity - $rest) / ($this->discountValue);
        return $freeItems * $unitPrice;
    }

    #[Pure]
    public function equals(Discount $discount): bool
    {
        return (
            $this->type === $discount->getType() &&
            $this->discountValue === $discount->getDiscountValue()
        );
    }
}