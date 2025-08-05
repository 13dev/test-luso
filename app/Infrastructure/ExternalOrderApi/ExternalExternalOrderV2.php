<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalOrderApi;

use App\Application\Contracts\ExternalOrderApiInterface;
use App\Domain\Order\ValueObjects\MoneyValueObject;
use League\Uri\Uri;

class ExternalExternalOrderV2 implements ExternalOrderApiInterface
{
    public function preparePayload(array $orderData): array
    {
        return [
            'data' => [
                'type' => 'orders',
                'attributes' => [
                    'customer' => [
                        'name' => $orderData['customer_name'],
                        'nif' => $orderData['customer_nif'],
                    ],
                    'summary' => [
                        'currency' => 'EUR',
                        'total' => MoneyValueObject::fromDecimal($orderData['total'])->toDecimal(),
                    ],
                    'lines' => array_map(fn ($item) => [
                        'sku' => $item['sku'],
                        'qty' => $item['qty'],
                        'price' => $item['unit_price']
                    ], $orderData['items'])
                ]
            ]
        ];
    }

    public function parseResponse(array $response): array
    {
        return [
            'number' => $response['data']['attributes']['number'],
            'uuid' => $response['data']['attributes']['uuid']
        ];
    }

    public function getUri(): Uri
    {
        return Uri::new(config('external_order.v2.uri'));
    }
}
