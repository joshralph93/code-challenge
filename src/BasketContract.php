<?php

declare(strict_types=1);

namespace Howsy\CodeChallenge;

use Howsy\CodeChallenge\Entities\Product;
use Howsy\CodeChallenge\ValueObjects\Money;

interface BasketContract
{
    public function add(Product $product, int $quantity): static;
    public function total(): Money;
    public function getItems(): array;
}