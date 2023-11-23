<?php

declare(strict_types=1);

namespace Howsy\CodeChallenge\ValueObjects;

class Money
{
    private int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function add(Money $money): Money
    {
        return new static($this->value + $money->value());
    }
}