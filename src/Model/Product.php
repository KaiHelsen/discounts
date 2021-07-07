<?php
declare(strict_types=1);

namespace App\Model;


use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class Product implements \JsonSerializable, JsonDecodeable
{
    private int $id;
    private string $description;
    private int $category;
    private float $price;

    #region constants
    public const ID = 'id';
    public const DESCRIPTION = 'description';
    public const CATEGORY = 'category';
    public const PRICE = 'price';
    #endregion

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


    #[ArrayShape([self::ID => "int", self::DESCRIPTION => "string", self::CATEGORY => "int", self::PRICE => "float"])] public function jsonSerialize(): array
    {
        return [
            self::ID => $this->id,
            self::DESCRIPTION => $this->description,
            self::CATEGORY => $this->category,
            self::PRICE => $this->price
        ];
    }

    #[Pure]
    public static function fromArray(array $input): self
    {
        //TODO: validate input
        return new self(
            (int)$input[self::ID],
            $input[self::DESCRIPTION],
            (int)$input[self::CATEGORY],
            (float)$input[self::PRICE]
        );
    }
}