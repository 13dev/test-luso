<?php

namespace Tests\Unit\Infrastructure;

use App\Infrastructure\DTOs\InternalOrderData;
use App\Infrastructure\Mappers\OrderApiDataMapper;
use InvalidArgumentException;
use Spatie\LaravelData\Contracts\BaseData;

beforeEach(function () {
    $this->fakeData = InternalOrderData::from(
        [
            'customer' => [
                'full_name' => 'John Doe',
                'nif' => '123456789',
            ],
            'total' => [
                'amount' => '1000',
                'currency' => 'EUR',
            ],
            'items' => [
                [
                    'sku' => 'ABC123',
                    'qty' => 2,
                    'unit_price' => ['amount' => '500', 'currency' => 'EUR',],
                ],
            ],
        ]
    );

    expect($this->fakeData)->toBeInstanceOf(BaseData::class);

});

it('maps data correctly for v1', function () {
    $mapped = OrderApiDataMapper::map($this->fakeData, 'v1');

    expect($mapped)->toMatchArray([
        'customer_name' => 'John Doe',
        'customer_nif' => '123456789',
        'total' => '1000',
        'items' => [
            [
                'sku' => 'ABC123',
                'qty' => 2,
                'unit_price' => '500',
            ],
        ],
        'currency' => 'EUR',
    ]);
});

it('maps data correctly for v2', function () {
    $mapped = OrderApiDataMapper::map($this->fakeData, 'v2');

    expect($mapped)->toMatchArray([
        'data' => [
            'type' => 'orders',
            'attributes' => [
                'customer' => [
                    'name' => 'John Doe',
                    'nif' => '123456789',
                ],
                'summary' => [
                    'currency' => 'EUR',
                    'total' => '1000',
                ],
                'lines' => [
                    [
                        'sku' => 'ABC123',
                        'qty' => 2,
                        'price' => '500',
                    ],
                ],
            ],
        ],
    ]);
});

it('throws exception for unsupported version', function () {
    expect(fn() => OrderApiDataMapper::map($this->fakeData, 'v3'))
        ->toThrow(InvalidArgumentException::class, 'Unsupported version v3');
});
