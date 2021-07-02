<?php
declare(strict_types=1);

namespace App\Model\ProviderCondition;

use App\Model\Discount\Discount;
use App\Model\Discount\IDiscount;
use App\Model\Order;

class CategoryCondition extends ProviderCondition
{
    /**
     * @var int[]
     */
    private array $validCategories;

    public function __construct(IDiscount $discount, ...$validCategories)
    {
        parent::__construct($discount);
        $this->validCategories = $validCategories;
    }

    public function Evaluate(Order $order): void
    {
        $items = $order->getItems();

        foreach ($items as $item)
        {
            if (in_array($item->getProduct()->getCategory(), $this->validCategories, true))
            {
                $item->addDiscount($this->discount);
            }
        }
    }
}