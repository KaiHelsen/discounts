<?php
declare(strict_types=1);

namespace App\Model\ProviderCondition;

use App\Model\Discount\Discount;
use App\Model\Order;
use JetBrains\PhpStorm\Pure;

class CategoryCondition extends ProviderCondition
{
    /**
     * @var int[]
     */
    private array $validCategories;

    /**
     * CategoryCondition constructor.
     * @param Discount $discount
     * @param int[] ...$validCategories
     */
    #[Pure]
    public function __construct(Discount $discount, array $validCategories)
    {
        parent::__construct($discount);
        $this->validCategories = $validCategories;
    }

    public function Evaluate(Order $order): bool
    {
        $items = $order->getItems();
        $addedDiscount = false;

        foreach ($items as $item)
        {
            if (in_array($item->getProduct()->getCategory(), $this->validCategories, true))
            {
                $item->addDiscount($this->discount);
                $addedDiscount = true;
            }
        }

        return $addedDiscount;
    }
}