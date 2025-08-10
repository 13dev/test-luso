<?php

namespace Tests\Unit\Domain\Order;


use App\Domain\Order\AggregateRoots\OrderAggregateRoot;
use App\Domain\Order\Events\OrderCreated;
use App\Domain\Order\Events\OrderIntegrated;
use App\Domain\Order\Events\OrderIntegrationFailed;
use App\Domain\Order\ValueObjects\CustomerValueObject;
use App\Domain\Order\ValueObjects\MoneyValueObject;
use App\Domain\Order\ValueObjects\TaxIdValueObject;

beforeEach(function () {
    $this->customer = new CustomerValueObject('John Doe', new TaxIdValueObject('123456789'));
    $this->total = MoneyValueObject::from(1000);
    $this->items = [
        ['sku' => 'sku1', 'qty' => 2, 'unit_price' => 500],
        ['sku' => 'sku2', 'qty' => 1, 'unit_price' => 500],
    ];
});

it('records OrderCreated event on create', function () {
    OrderAggregateRoot::fake()
        ->when(fn(OrderAggregateRoot $order) => $order->create($this->customer, $this->total, $this->items))
        ->assertRecorded(new OrderCreated($this->customer, $this->total, $this->items))
        ->assertNotRecorded(OrderIntegrated::class)
        ->assertNotRecorded(OrderIntegrationFailed::class);
});

it('records OrderIntegrated event and updates status', function () {
    OrderAggregateRoot::fake()
        ->given([
            new OrderCreated($this->customer, $this->total, $this->items),
        ])
        ->when(fn(OrderAggregateRoot $order) => $order->markAsIntegrated('EXT123', 'uuid-456'))
        ->assertRecorded(new OrderIntegrated('EXT123', 'uuid-456'))
        ->assertNotRecorded(OrderIntegrationFailed::class);
});

it('records OrderIntegrationFailed event and updates status', function () {
    OrderAggregateRoot::fake()
        ->given([
            new OrderCreated($this->customer, $this->total, $this->items),
        ])
        ->when(fn(OrderAggregateRoot $order) => $order->markAsFailed('Some error'))
        ->assertRecorded(new OrderIntegrationFailed('Some error'))
        ->assertNotRecorded(OrderIntegrated::class);
});
