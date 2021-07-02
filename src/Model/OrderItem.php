<?php
declare(strict_types=1);

namespace App\Model;


use App\Model\Discount\IDiscount;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;

class OrderItem implements JsonSerializable, IDiscountable
{
    private Product $product;
    private int $quantity;
    private float $unitPrice;
    private float $totalPrice;
    private float $discountedPrice;
    /**
     * @var IDiscount[]
     */
    private array $discounts = [];

    private const ID = 'product-id';
    private const QUANTITY = 'quantity';
    private const UNIT_PRICE = 'unit-price';
    private const TOTAL_PRICE = 'total';

    public function __construct(Product $product, int $quantity, float $unitPrice = -1)
    {
        $this->product = $product;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice > 0 ? $unitPrice : $product->getPrice();
        $this->totalPrice = $this->unitPrice * $quantity;

    }

    /**
     * @return Product
     */
    #[Pure]
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return int
     */
    #[Pure]
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return float
     */
    #[Pure]
    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    /**
     * @return float
     */
    #[Pure]
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function addDiscount(IDiscount $discount): void
    {
        $this->discounts[] = $discount;
    }

    #[Pure]
    #[ArrayShape([self::ID => "int", self::QUANTITY => "int", self::UNIT_PRICE => "float", self::TOTAL_PRICE => "float|int"])]
    public function jsonSerialize(): array
    {
        return [
            self::ID => $this->product->getId(),
            self::QUANTITY => $this->quantity,
            self::UNIT_PRICE => $this->unitPrice,
            self::TOTAL_PRICE => $this->totalPrice,
        ];
    }

    #[Pure]
    public static function fromArray(array $input): self
    {
        return new self(
            $input[self::ID],
            $input[self::QUANTITY],
            $input[self::UNIT_PRICE],
        );
    }

    public function applyDiscounts(): void
    {
        $this->discountedPrice = $this->totalPrice;
        foreach ($this->discounts as $discount)
        {
            $this->discountedPrice -= $discount->calculateDiscountedPrice($this);
        }
    }

    public function getDiscountedPrice(): float
    {
        $this->applyDiscounts();
        return $this->discountedPrice;
    }

    #[Pure]
    public function isCheaperThan(?OrderItem $item): bool
    {
        //we assume that if an item is null, it is more expensive than any real item
        if ($item === null)
        {
            return true;
        }
        return $this->unitPrice < $item->getUnitPrice();
    }
}