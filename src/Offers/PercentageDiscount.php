<?php

declare(strict_types=1);

namespace Howsy\CodeChallenge\Offers;

use Howsy\CodeChallenge\BasketContract;
use Howsy\CodeChallenge\Entities\Product;
use Howsy\CodeChallenge\ValueObjects\Money;
use Howsy\CodeChallenge\ValueObjects\ProductCode;

class PercentageDiscount implements Offer
{
    public function __construct(
        private readonly string $amount
    ) {
        // Could do some work here to validate the amount given.
    }

    public function validateEligibility(BasketContract $basket): bool
    {
        return true;

        // For the sake of example this method just returns true so the discount is always applied. However,
        // realistically there are a whole host of things you could do here to validate whether the offer
        // should be applied to the basket. In the example we're considering, this may look like:
        /** @var Product $item */
        foreach ($basket->getItems() as $item) {
            if ($item->code()->equals(new ProductCode('12_MONTH_CONTRACT'))) {
                return true;
            }
        }

        return false;
    }

    public function apply(Money $value): Money
    {
        $multiplier = 1 - (floatval($this->amount) / 100);

        return new Money(max(0, intval($value->value() * $multiplier)));
    }

    public function description(): string
    {
        return sprintf('%s%% off', $this->amount);
    }
}