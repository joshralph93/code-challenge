<?php

declare(strict_types=1);

namespace Howsy\CodeChallenge\Entities;

use Howsy\CodeChallenge\ValueObjects\Money;
use Howsy\CodeChallenge\ValueObjects\ProductCode;

class Product
{
    private ProductCode $code;
    private string $name;
    private Money $price;
    private int $maxOrderQuantity;

    public function __construct(ProductCode $code, string $name, Money $price, int $maxOrderQuantity = 1) {
        $this->code = $code;
        $this->name = $name;
        $this->price = $price;
        $this->maxOrderQuantity = $maxOrderQuantity;
    }

    public function code(): ProductCode
    {
        return $this->code;
    }

    public function price(): Money
    {
        return $this->price;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function maxOrderQuantity(): int
    {
        return $this->maxOrderQuantity;
    }
}