<?php

declare(strict_types=1);

namespace App\Domain\Order\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class OrderIntegrationFailed extends ShouldBeStored
{
    public function __construct(public string $reason)
    {
    }
}
