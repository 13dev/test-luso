<?php

declare(strict_types=1);

namespace App\Infrastructure\Order;

use App\Domain\Order\Enums\OrderStatusEnum;
use App\Domain\Order\Events\OrderCreated;
use App\Domain\Order\Events\OrderIntegrated;
use App\Domain\Order\Order;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class OrderProjector extends Projector
{
    public function onOrderCreated(OrderCreated $event): void
    {
        Order::create([
            'uuid' => $event->aggregateRootUuid(),
            'customer_name' => $event->customer->name,
            'customer_nif' => (string) $event->customer->nif,
            'total' => $event->total->toDecimal(),
            'items' => $event->items,
            'status' => OrderStatusEnum::Created,
        ]);
    }

    public function onOrderIntegrated(OrderIntegrated $event): void
    {
        $order = Order::where('uuid', $event->aggregateRootUuid())->first();
        $order->update([
            'external_number' => $event->externalNumber,
            'external_uuid' => $event->externalUuid,
            'status' => OrderStatusEnum::Integrated,
        ]);
    }
}
