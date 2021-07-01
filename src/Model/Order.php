<?php
declare(strict_types=1);

namespace App\Model;


use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;

class Order implements JsonSerializable
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
     * @param float $totalPrice
     */
    public function __construct(int $id, Customer $customer, array $items, float $totalPrice)
    {
        $this->id = $id;
        $this->customer = $customer;
        $this->items = [];
        $this->items[] = $items;
        $this->discounts = [];
        $this->totalPrice = $totalPrice;
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

    public function recalculateTotalPrice() : void
    {
        $totalPrice = 0;
        foreach($this->items as $item)
        {
            $totalPrice += $item->getTotalPrice();
        }
        //TODO: apply discount(s) to order
        $this->totalPrice = $totalPrice;
    }

    #[Pure]
    #[ArrayShape([self::ID => "int", self::CUSTOMER_ID => "int", self::ITEMS => "\App\Model\OrderItem[]|array", self::DISCOUNTS => "\App\Model\Discount[]|array", self::TOTAL_PRICE => "float"])]
    public function jsonSerialize() : array
    {
        return [
            self::ID => $this->id,
            self::CUSTOMER_ID => $this->customer->getId(),
            self::ITEMS => $this->items,
            self::DISCOUNTS => $this->discounts,
            self::TOTAL_PRICE => $this->totalPrice,
        ];
    }

    #[Pure]
    public static function fromArray(array $input) : self
    {
        return new self(
            $input[self::ID],
            $input[self::CUSTOMER_ID],
            $input[self::ITEMS],
            $input[self::TOTAL_PRICE],
        );
    }
}