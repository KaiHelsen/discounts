<?php


namespace App\Model\ProviderCondition;


use App\Model\Discount\Discount;
use App\Model\Order;

class CheapestInCategoryCondition extends ProviderCondition
{
    /**
     * @var int[]
     */
    private array $validCategories;
    private int $minItems;

    /**
     * CheapestInCategoryCondition constructor.
     * @param Discount $discount
     * @param int $minItems
     * @param int[] $validCategories
     */
    public function __construct(Discount $discount, int $minItems, array $validCategories = [])
    {
        parent::__construct($discount);
        $this->minItems = $minItems;
        $this->validCategories = $validCategories;
    }

    public function Evaluate(Order $order): bool
    {

        $items = $order->getItems();
        if(empty($items))
        {
            return false;
        }

        $cheapestProduct = $items[0];
        $toolCount = 0;

        foreach ($items as $item)
        {
            if(empty($this->validCategories) ||
                in_array($item->getProduct()->getCategory(), $this->validCategories, true))
            {
                $toolCount++;
                $cheapestProduct = $item->isCheaperThan($cheapestProduct)?$item: $cheapestProduct;
            }
        }

        if ($toolCount >= $this->minItems)
        {
            //TODO: add 20% discount on cheapest product
            $cheapestProduct->addDiscount(Discount::newVariableDiscount(20));
            return true;
        }

        return false;
    }
}