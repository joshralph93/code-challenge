<?php

declare(strict_types=1);

namespace Howsy\CodeChallenge\Offers;

use Howsy\CodeChallenge\BasketContract;
use Howsy\CodeChallenge\ValueObjects\Money;

interface Offer
{
    public function validateEligibility(BasketContract $basket): bool;
    public function apply(Money $value): Money;
    public function description(): string;
}