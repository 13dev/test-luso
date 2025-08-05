<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalOrderApi;

use App\Application\Contracts\ExternalOrderApiInterface;
use App\Domain\Order\ValueObjects\MoneyValueObject;
use League\Uri\Uri;


class ExternalExternalOrderV1 implements ExternalOrderApiInterface
{
    public function preparePayload(array $orderData): array
    {
        return [
            'customer_name' => $orderData['customer_name'],
            'customer_nif' => $orderData['customer_nif'],
            'total' => MoneyValueObject::fromDecimal($orderData['total'])->toDecimal(),
            'currency' => 'EUR',
            'items' => array_map(fn ($item) => [
                'sku' => $item['sku'],
                'qty' => $item['qty'],
                'unit_price' => $item['unit_price']
            ], $orderData['items'])
        ];
    }

    public function parseResponse(array $response): array
    {
        return [
            'number' => $response['data']['number'],
            'uuid' => $response['data']['uuid']
        ];
    }

    public function getUri(): Uri
    {
        return Uri::new(config('external_order.v1.uri'));
    }
}
