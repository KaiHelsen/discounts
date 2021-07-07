<?php
declare(strict_types=1);

namespace App\Model;

use DateTime;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;

class Customer implements JsonSerializable, JsonDecodeable
{
    private int $id;
    private string $name;
    private DateTime $since;
    private float $revenue;

    #region constants
    public const ID = 'id';
    public const NAME = 'name';
    public const SINCE = 'since';
    public const REVENUE = 'revenue';
    public const DATETIME_FORMAT = "Y-m-d";
    #endregion

    public function __construct(int $id, string $name, DateTime $since, float $revenue)
    {
        $this->id = $id;
        $this->name = $name;
        $this->since = $since;
        $this->revenue = $revenue;
    }

    #[Pure]
    public function getId(): int
    {
        return $this->id;
    }

    #[Pure]
    public function getName(): string
    {
        return $this->name;
    }

    public function getSinceFormatted(string $format = self::DATETIME_FORMAT): string
    {
        return $this->since->format($format);
    }

    #[Pure]
    public function getSince(): DateTime
    {
        return $this->since;
    }

    #[Pure]
    public function getRevenue(): float
    {
        return $this->revenue;
    }

    #[ArrayShape([self::ID => "int", self::NAME => "string", self::SINCE => "string", self::REVENUE => "float"])]
    public function jsonSerialize(): array
    {
        return [
            self::ID => $this->id,
            self::NAME => $this->name,
            self::SINCE => $this->getSinceFormatted(),
            self::REVENUE => $this->revenue
        ];
    }

    public static function fromArray(array $input): self
    {
        //TODO: validate input
        return new self(
            (int)$input[self::ID],
            $input[self::NAME],
            DateTime::createFromFormat(self::DATETIME_FORMAT, $input[self::SINCE]),
            (float)$input[self::REVENUE]
        );
    }
}