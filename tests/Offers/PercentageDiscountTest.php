<?php

namespace Howsy\Tests\ValueObjects;

use Howsy\CodeChallenge\Offers\PercentageDiscount;
use Howsy\CodeChallenge\ValueObjects\Money;
use PHPUnit\Framework\TestCase;

class PercentageDiscountTest extends TestCase
{
    /** @test */
    public function can_apply_percentage_discount()
    {
        $discount = new PercentageDiscount('20');

        $this->assertEquals(80_00, $discount->apply(new Money(100_00))->value());
    }

    /** @test */
    public function can_apply_subunit_percentage_discount()
    {
        $discount = new PercentageDiscount('17.5');

        $this->assertEquals(82_50, $discount->apply(new Money(100_00))->value());
    }

    /** @test */
    public function can_apply_percentage_discount_to_zero_value()
    {
        $discount = new PercentageDiscount('20');

        $this->assertEquals(0, $discount->apply(new Money(0))->value());
    }

    /** @test */
    public function can_apply_more_than_100_percentage_discount()
    {
        $discount = new PercentageDiscount('110');

        $this->assertEquals(0, $discount->apply(new Money(100_00))->value());
    }
}