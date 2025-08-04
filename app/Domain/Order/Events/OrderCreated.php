<?php

declare(strict_types=1);

namespace App\Domain\Order\Events;

use App\Domain\Order\ValueObjects\CustomerValueObject;
use App\Domain\Order\ValueObjects\MoneyValueObject;
use App\Domain\Order\ValueObjects\TaxIdValueObject;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class OrderCreated extends ShouldBeStored
{
    public function __construct(
        public CustomerValueObject $customer,
        public MoneyValueObject $total,
        public array $items
    ) {
    }
}
