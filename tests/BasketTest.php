<?php

namespace Howsy\Tests;

use Howsy\CodeChallenge\Basket;
use Howsy\CodeChallenge\Entities\Product;
use Howsy\CodeChallenge\Exceptions\MaxOrderQuantityExceeded;
use Howsy\CodeChallenge\Offers\Offer;
use Howsy\CodeChallenge\Offers\PercentageDiscount;
use Howsy\CodeChallenge\ValueObjects\Money;
use Howsy\CodeChallenge\ValueObjects\ProductCode;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\TestCase;

class BasketTest extends TestCase
{
    /** @test */
    public function can_initialise_basket_with_offers()
    {
        $basket = new Basket([
            new PercentageDiscount('10'),
        ]);

        $this->assertCount(1, $basket->getOffers());
    }

    /** @test */
    public function cannot_exceed_product_max_order_quantity_when_adding_to_basket()
    {
        $this->expectException(MaxOrderQuantityExceeded::class);

        $basket = new Basket();
        $product = new Product(ProductCode::from('P001'), 'Photography', new Money(200_00), 1);

        $basket->add($product, 2);
    }

    /** @test */
    public function cannot_add_zero_quantity_of_product_to_the_basket()
    {
        $this->expectException(InvalidArgumentException::class);

        $basket = new Basket();
        $product = new Product(ProductCode::from('P001'), 'Photography', new Money(200_00), 1);

        $basket->add($product, 0);
    }

    /** @test */
    public function cannot_add_negative_quantity_of_product_to_the_basket()
    {
        $this->expectException(InvalidArgumentException::class);

        $basket = new Basket();
        $product = new Product(ProductCode::from('P001'), 'Photography', new Money(200_00), 1);

        $basket->add($product, -1);
    }

    /** @test */
    public function can_add_product_to_the_basket()
    {
        $basket = new Basket();
        $product = new Product(ProductCode::from('P001'), 'Photography', new Money(200_00), 1);

        $basket->add($product);
        $this->assertCount(1, $basket->getItems());
    }

    /** @test */
    public function can_add_product_to_the_basket_multiple_times_when_max_order_value_allows()
    {
        $basket = new Basket();
        $product = new Product(ProductCode::from('P001'), 'Photography', new Money(200_00), 5);

        $basket->add($product);
        $basket->add($product, 2);
        $this->assertCount(3, $basket->getItems());
    }

    /** @test */
    public function can_retrieve_total_value_of_an_empty_basket_without_offers()
    {
        $basket = new Basket();

        $this->assertEquals(0, $basket->total()->value());
    }

    /** @test */
    public function can_retrieve_total_value_of_an_empty_basket_with_offers()
    {
        $basket = new Basket([
            new PercentageDiscount('25'),
        ]);

        $this->assertEquals(0, $basket->total()->value());
    }

    /** @test */
    public function can_retrieve_total_value_of_basket_with_items_and_no_offers()
    {
        $basket = new Basket();
        $product1 = new Product(ProductCode::from('P001'), 'Photography', new Money(200_00), 2);
        $product2 = new Product(ProductCode::from('P002'), 'Floorplan', new Money(100_00), 1);
        $product3 = new Product(ProductCode::from('P003'), 'Gas Certificate', new Money(83_50), 5);
        $product4 = new Product(ProductCode::from('P004'), 'EICR Certificate', new Money(51_00), 5);

        $basket
            ->add($product1)
            ->add($product2)
            ->add($product3, 2)
            ->add($product4, 2);

        $this->assertSame(569_00, $basket->total()->value());
    }

    /** @test */
    public function can_retrieve_total_value_of_basket_with_items_and_offers()
    {
        $basket = new Basket([
            new PercentageDiscount('20'),
        ]);
        $product1 = new Product(ProductCode::from('P001'), 'Photography', new Money(200_00));
        $product2 = new Product(ProductCode::from('P002'), 'Floorplan', new Money(100_00));
        $product3 = new Product(ProductCode::from('P003'), 'Gas Certificate', new Money(83_50));
        $product4 = new Product(ProductCode::from('P004'), 'EICR Certificate', new Money(51_00));

        $basket
            ->add($product1)
            ->add($product2)
            ->add($product3)
            ->add($product4);

        $this->assertSame(347_60, $basket->total()->value());
    }

    /** @test */
    public function can_retrieve_total_value_of_basket_with_items_and_multiple_offers()
    {
        $basket = new Basket([
            new PercentageDiscount('25'),
            new PercentageDiscount('10'),
        ]);

        $basket->add(
            new Product(ProductCode::from('P001'), 'Photography', new Money(200_00))
        );

        // 200_00 - 25% = 150_00
        // 150_00 - 10% = 135_00
        $this->assertSame(135_00, $basket->total()->value());
    }

    /** @test */
    public function cannot_apply_ineligible_offers_to_basket_total()
    {
        $basket = new Basket([
            Mockery::mock(Offer::class)->shouldReceive('validateEligibility')->andReturnFalse()->getMock(),
        ]);

        $basket->add(
            new Product(ProductCode::from('P001'), 'Photography', new Money(200_00))
        );

        $this->assertSame(200_00, $basket->total()->value());
    }
}