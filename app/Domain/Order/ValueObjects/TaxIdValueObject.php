<?php

namespace App\Domain\Order\ValueObjects;

class TaxIdValueObject
{
    public function __construct(private readonly string $value)
    {
        if (!preg_match('/^\d{9}$/', $value)) {
            throw new InvalidTaxIdException();
        }
    }
}
