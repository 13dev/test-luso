<?php

declare(strict_types=1);

namespace App\Domain\Order\ValueObjects;

final class CustomerValueObject
{
    public function __construct(
        public string $name,
        public TaxIdValueObject $nif,
    ) {
    }
}
