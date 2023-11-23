<?php

declare(strict_types=1);

namespace Howsy\CodeChallenge\ValueObjects;

class ProductCode
{
    private string $value;

    public function __construct(string $value)
    {
        // We could consider doing some validation at this point and
        // throwing an exception if values are invalid, but for the
        // example we can just initialise with the given string.
        $this->value = $value;
    }

    public static function from(self|string $code): static
    {
        return new static($code instanceof self ? $code->value() : $code);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value() === $other->value();
    }
}