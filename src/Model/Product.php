<?php
declare(strict_types=1);

namespace App\Model;


use JetBrains\PhpStorm\Pure;

class Product
{
    private int $id;
    private string $description;
    private int $category;
    private float $price;

    public function __construct(int $id, string $description, int $category, float $price)
    {
        $this->id = $id;
        $this->description = $description;
        $this->category = $category;
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getCategory(): int
    {
        return $this->category;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    #[Pure]
    public static function findCheapestProduct(Product ...$products) : self
    {
        $cheapest = $products[0];

        foreach($products as $product)
        {
            if($product->getPrice() < $cheapest->getPrice())
            {
                $cheapest = $product;
            }
        }
        return $cheapest;
    }
}