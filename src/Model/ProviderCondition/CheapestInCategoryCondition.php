<?php


namespace App\Model\ProviderCondition;


use App\Model\Discount\Discount;
use App\Model\Discount\IDiscount;
use App\Model\Order;

class CheapestInCategoryCondition extends ProviderCondition
{
    /**
     * @var int[]
     */
    private array $validCategories;
    private int $minItems;

    public function __construct(IDiscount $discount, int $minItems, int ...$validCategories)
    {
        parent::__construct($discount);
        $this->minItems = $minItems;
        $this->validCategories = $validCategories;
    }

    public function Evaluate(Order $order): void
    {
        $items = $order->getItems();
        $cheapestProduct = $items[0];
        $toolCount = 0;

        foreach ($items as $item)
        {
            if(in_array($item->getProduct()->getCategory(), $this->validCategories, true))
            {
                $toolCount++;
                $cheapestProduct = $item->isCheaperThan($cheapestProduct);
            }
        }

        if ($toolCount >= $this->minItems)
        {
            //TODO: add 20% discount on cheapest product
            $cheapestProduct->addDiscount(Discount::newVariableDiscount(20));
        }
    }
}