<?php

namespace App\Domain\Order\ValueObjects;

use Stringable;

class TaxIdValueObject implements Stringable
{
    public function __construct(private readonly string $value)
    {
        if (!preg_match('/^\d{9}$/', $value)) {
            throw new \RuntimeException('Invalid tax id value');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
