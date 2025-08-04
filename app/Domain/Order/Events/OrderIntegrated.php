<?php

declare(strict_types=1);

namespace App\Domain\Order\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class OrderIntegrated extends ShouldBeStored
{
    public function __construct(
        public string $externalNumber,
        public string $externalUuid
    ) {
    }
}
