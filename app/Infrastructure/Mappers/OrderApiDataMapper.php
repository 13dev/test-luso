<?php

namespace App\Infrastructure\Mappers;

class OrderApiDataMapper
{
    public static function map($data, string $version): array
    {
        return match ($version) {
            'v1' => self::mapToV1($data),
            'v2' => self::mapToV2($data),
            default => throw new \InvalidArgumentException("Unsupported version {$version}"),
        };
    }

    protected static function mapToV1($data): array
    {
        return [
            'customer_name' => $data->customer->name,
            'customer_nif' => (string) $data->customer->nif,
            'total' => $data->total->getAmount(),
            'items' => $data->items->toArray(),
            'currency' => $data->total->getCurrency(),
        ];

    }


    protected static function mapToV2($data): array
    {
        return [
            'data' => [
                'type' => 'orders',
                'attributes' => [
                    'customer' => [
                        'name' => $data->customer->name,
                        'nif' => (string) $data->customer->nif,
                    ],
                    'summary' => [
                        'currency' => $data->total->getCurrency(),
                        'total' => $data->total->getAmount(),
                    ],
                    'lines' => collect($data->items)
                        ->map(function ($item) {
                            return [
                                'sku' => $item['sku'],
                                'qty' => $item['qty'],
                                'price' => $item['unit_price']['amount'],
                            ];
                        })->toArray(),
                ],
            ],
        ];


    }
}
