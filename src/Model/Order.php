<?php
declare(strict_types=1);

namespace App\Model;


use App\Model\Discount\Discount;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;

class Order implements JsonSerializable, IDiscountable
{
    private int $id;
    private Customer $customer;
    /**
     * @var OrderItem[]
     */
    private array $items;
    /**
     * @var Discount[]
     */
    private array $discounts;
    private float $totalPrice;
    private float $discountedPrice;

    private const ID = 'id';
    private const CUSTOMER_ID = 'customer-id';
    private const ITEMS = 'items';
    private const DISCOUNTS = 'discounts';
    private const TOTAL_PRICE = 'total';

    /**
     * Order constructor.
     * @param int $id
     * @param Customer $customer
     * @param OrderItem[] $items
     */
    public function __construct(int $id, Customer $customer, array $items)
    {
        $this->id = $id;
        $this->customer = $customer;
        $this->items = $items;
        $this->discounts = [];

        $this->calculatePrices();
    }

    /**
     * @param $input
     * @param Product[] $products
     * @return array
     */
    private static function generateItems($input, array $products): array
    {
        $items = [];
        foreach ($input as $item)
        {
            foreach ($products as $product)
            {
                if ($item['id'] === $product->getId())
                {
                    $items[] = new OrderItem($product, $item['quantity'], $item['unit-price']);
                }
            }
        }
        return $items;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @return OrderItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return float
     */
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    /**
     * @return Discount[]
     */
    public function getDiscounts(): array
    {
        return $this->discounts;
    }

    public function calculatePrices(): void
    {
        $this->calculateTotalPrice();
        $this->applyDiscounts();
    }

    public function addDiscount(Discount $discount): void
    {
        $this->discounts[] = $discount;
    }

    public function removeDiscount(Discount $discount): void
    {
        foreach ($this->discounts as $i => $iValue)
        {
            if ($iValue->equals($discount))
            {
                unset($this->discounts[$i]);
            }
        }
    }

    public function applyDiscounts(): void
    {
        $this->discountedPrice = 0;

        $this->applyDiscountsToItems();

        $this->applyDiscountsToSelf();
    }

    public function getDiscountedPrice(): float
    {
        $this->applyDiscounts();
        return $this->discountedPrice;
    }

    public function getUnitPrice(): float
    {
        return $this->getDiscountedPrice();
    }

    public function getQuantity(): int
    {
        //there will always ever be only one order.
        return 1;
    }

    private function calculateTotalPrice(): void
    {
        $totalPrice = 0;
        foreach ($this->items as $item)
        {
            $totalPrice += $item->getTotalPrice();
        }
        $this->totalPrice = $totalPrice;
    }

    #region Serialization
    #[Pure]
    #[ArrayShape([self::ID => "int", self::CUSTOMER_ID => "int", self::ITEMS => "\App\Model\OrderItem[]|array", self::DISCOUNTS => "\App\Model\Discount[]|array", self::TOTAL_PRICE => "float"])]
    public function jsonSerialize(): array
    {
        return [
            self::ID => $this->id,
            self::CUSTOMER_ID => $this->customer->getId(),
            self::ITEMS => $this->items,
            self::DISCOUNTS => $this->discounts,
            self::TOTAL_PRICE => $this->totalPrice,
        ];
    }

    /**
     * @param array $input
     * @param Product[] $products
     * @param Customer[] $customers
     * @return static
     */
    public static function fromArray(array $input, array $products, array $customers): self
    {
        $items = self::generateItems($input[self::ITEMS], $products);

        $customer = self::findCustomer($customers, $input[self::CUSTOMER_ID]);
        return new self(
            $input[self::ID],
            $input[self::CUSTOMER_ID],
            $items,
        );
    }

    #endregion
    private function applyDiscountsToItems(): void
    {
        foreach ($this->items as $item)
        {
            $this->discountedPrice += $item->getDiscountedPrice();
        }
    }


    private function applyDiscountsToSelf(): void
    {
        $this->discountedPrice = $this->totalPrice;
        foreach ($this->discounts as $discount)
        {
            $this->discountedPrice -= $discount->calculateDiscountedPrice($this);
        }
    }

    /**
     * @param Customer[] $customers
     * @param int $customerId
     * @return Customer
     */
    #[Pure]
    private static function findCustomer(array $customers, int $customerId): Customer
    {
        foreach ($customers as $customer)
        {
            if ($customer->getId() === $customerId)
            {
                return $customer;
            }
        }
    }
}