<?php

declare(strict_types=1);

namespace App\Infrastructure\Order;

use App\Application\Order\Services\ExternalOrderApiService;
use App\Application\VersionResolver;
use App\Domain\Exceptions\ExternalOrderApiRequestFailedException;
use App\Domain\Order\Events\OrderCreated;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class OrderReactor extends Reactor
{
    public function __construct(private readonly ExternalOrderApiService $externalOrderApiService)
    {
    }

    /**
     * @throws ExternalOrderApiRequestFailedException
     */
    public function onOrderCreated(OrderCreated $event): void
    {
        $response = $this->externalOrderApiService->call($event->data, VersionResolver::resolve());
    }
}
