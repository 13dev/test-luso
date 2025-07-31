<?php

namespace App\Domain\Order\ValueObjects;


use Cknow\Money\Money;

final class MoneyValueObject
{
    public function __construct(
        public readonly Money $money
    ) {
    }

    public static function fromDecimal(float $amount, string $currency = 'EUR'): self
    {
        return new self(money($amount, $currency));
    }

    public function toDecimal(): float
    {
        return $this->money->formatByDecimal();
    }

    public function getAmount(): int
    {
        return $this->money->getAmount();
    }
}
