<?php

declare(strict_types=1);

namespace Howsy\CodeChallenge;

use Howsy\CodeChallenge\Entities\Product;
use Howsy\CodeChallenge\Exceptions\MaxOrderQuantityExceeded;
use Howsy\CodeChallenge\Offers\Offer;
use Howsy\CodeChallenge\ValueObjects\Money;
use Howsy\CodeChallenge\ValueObjects\ProductCode;
use InvalidArgumentException;

class Basket implements BasketContract
{
    private array $items = [];
    private array $offers;

    public function __construct(array $offers = [])
    {
        $this->offers = array_map(function ($offer) {
            if (! $offer instanceof Offer) {
                throw new InvalidArgumentException('All offers must be an instance of ' . Offer::class);
            }

            return $offer;
        }, $offers);
    }

    public function add(Product $product, int $quantity = 1): static
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Quantity must be at least 1.');
        }

        // > "3. Each individual product can only be added to the basket one time"
        //
        // The code here enforces this rule, however affords additional flexibility by
        // deferring to a product's 'max order quantity' to enforce the quantity limit.
        if (count($this->getItemsByCode($product->code())) + $quantity > $product->maxOrderQuantity()) {
            throw new MaxOrderQuantityExceeded;
        }

        $this->items = array_merge($this->items, array_fill(0, $quantity, $product));

        return $this;
    }

    public function total(): Money
    {
        // First step is to sum up all the product prices to create a pre-offer total.
        $total = array_reduce(
            $this->items,
            fn (Money $total, Product $product) => $total->add($product->price()),
            new Money(0)
        );

        // Then we apply any offers which pass their respective eligibility check.
        return array_reduce(
            $this->offers,
            fn (Money $total, Offer $offer) => $offer->validateEligibility($this)
                ? $offer->apply($total)
                : $total,
            $total
        );
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getOffers(): array
    {
        return $this->offers;
    }

    private function getItemsByCode(ProductCode $code): array
    {
        return array_filter($this->items, fn (Product $product) => $product->code()->equals($code));
    }
}