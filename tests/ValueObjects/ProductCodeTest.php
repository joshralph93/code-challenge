<?php

namespace Howsy\Tests\ValueObjects;

use Howsy\CodeChallenge\ValueObjects\ProductCode;
use PHPUnit\Framework\TestCase;

class ProductCodeTest extends TestCase
{
    /** @test */
    public function can_initialise_product_code()
    {
        $this->assertInstanceOf(ProductCode::class, new ProductCode('P001'));
        $this->assertInstanceOf(ProductCode::class, ProductCode::from('P001'));

        $this->assertInstanceOf(ProductCode::class, $new = ProductCode::from($original = new ProductCode('P001')));
        $this->assertTrue($new !== $original);
    }

    /** @test */
    public function can_compare_equality()
    {
        $this->assertTrue((new ProductCode('P001'))->equals(new ProductCode('P001')));
        $this->assertTrue(($self = new ProductCode('P001'))->equals($self));
        $this->assertFalse((new ProductCode('P001'))->equals(new ProductCode('P002')));
    }

    /** @test */
    public function can_retrieve_value()
    {
        $this->assertEquals('P001', (new ProductCode('P001'))->value());
    }
}