<?php

declare(strict_types=1);

namespace App\Domain\Order\ValueObjects;

use Cknow\Money\Money;
use Money\Currency;


final class MoneyValueObject
{
    public function __construct(
        public readonly Money $money
    ) {
    }

    public static function from(string|float $amount, string $currency = 'EUR'): self
    {
        return new self(money((float) $amount, $currency));
    }

    public function toDecimal(): string
    {
        return $this->money->formatByDecimal();
    }


    public function getAmount(): string
    {
        return $this->money->getAmount();
    }
    public function getCurrency(): Currency
    {
        return $this->money->getCurrency();
    }

}
