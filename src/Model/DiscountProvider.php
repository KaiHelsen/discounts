<?php
declare(strict_types=1);

namespace App\Model;


use App\Model\Discount\Discount;
use App\Model\ProviderCondition\ProviderCondition;
use JetBrains\PhpStorm\Pure;

class DiscountProvider
{

    public const REVENUE_DISCOUNT_VALUE = 1000;
    public const TOOLS = 1;
    public const SWITCHES = 2;

    /**
     * @var ProviderCondition[] $condition
     */
    private array $conditions = [];

    public function __construct()
    {
        //TODO: I'm sure there's stuff to do here
    }

    public function addConditions(ProviderCondition $condition): void
    {
        $this->conditions[] = $condition;
    }

    public function getConditions(): array
    {
        return $this->conditions;
    }

    public function addDiscounts(Order $order): void
    {
        foreach ($this->conditions as $condition)
        {
            $condition->Evaluate($order);
        }
    }

}