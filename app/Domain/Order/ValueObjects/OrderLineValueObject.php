<?php

declare(strict_types=1);

namespace App\Domain\Order\ValueObjects;

final class OrderLineValueObject
{
    public function __construct(
        public string $sku,
        public int $qty,
        public float $unitPrice,
    ) {
    }

    public function subtotal(): float
    {
        return $this->qty * $this->unitPrice;
    }
}
