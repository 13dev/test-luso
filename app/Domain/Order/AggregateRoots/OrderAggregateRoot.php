<?php

declare(strict_types=1);

namespace App\Domain\Order\AggregateRoots;

use App\Domain\Order\Enums\OrderStatusEnum;
use App\Domain\Order\Events\OrderCreated;
use App\Domain\Order\Events\OrderIntegrated;
use App\Domain\Order\Events\OrderIntegrationFailed;
use App\Domain\Order\ValueObjects\CustomerValueObject;
use App\Domain\Order\ValueObjects\MoneyValueObject;
use App\Domain\Order\ValueObjects\TaxIdValueObject;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class OrderAggregateRoot extends AggregateRoot
{
    public string $number;
    public OrderStatusEnum $status = OrderStatusEnum::Pending;
    public MoneyValueObject $total;
    public CustomerValueObject $customer;
    public array $items;

    public function create(
        CustomerValueObject $customer,
        MoneyValueObject $total,
        array $items
    ): self {
        $this->recordThat(new OrderCreated(
            $customer,
            $total,
            $items
        ));

        return $this;
    }

    public function markAsIntegrated(string $externalNumber, string $externalUuid): self
    {
        $this->recordThat(new OrderIntegrated(
            $externalNumber,
            $externalUuid
        ));

        return $this;
    }

    public function markAsFailed(string $reason): self
    {
        $this->recordThat(new OrderIntegrationFailed($reason));
        return $this;
    }

    protected function applyOrderCreated(OrderCreated $event): void
    {
        $this->customer = $event->customer;
        $this->total = $event->total;
        $this->items = $event->items;
    }

    protected function applyOrderIntegrated(OrderIntegrated $event): void
    {
        $this->number = $event->externalNumber;
        $this->status = OrderStatusEnum::Integrated;
    }

    protected function applyOrderIntegrationFailed(OrderIntegrationFailed $event): void
    {
        $this->status = OrderStatusEnum::Failed;
    }
}
