<?php

namespace App\Infrastructure\Mappers;

use App\Domain\Order\ValueObjects\MoneyValueObject;
use App\Infrastructure\ExternalOrderApi\DTOs\ExternalOrderItemData;
use Spatie\LaravelData\Contracts\BaseData;
use Spatie\LaravelData\Data;

class OrderApiDataMapper
{
    public static function map(array $externalData, string $version): array
    {
        return match ($version) {
            'v1' => self::mapToV1($externalData),
            'v2' => self::mapToV2($externalData),
            default => throw new \InvalidArgumentException("Unsupported version {$version}"),
        };
    }

    protected static function mapToV1(array $data): array
    {
        return [
            'customer_name' => $data['customer_name'] ?? '',
            'customer_nif' => $data['customer_nif'] ?? '',
            'total' => $data['total'],
            'items' => $data['items'] ?? [],
            'currency' => $data['currency'],
        ];

    }


    protected static function mapToV2(array $data): array
    {
        return [
            'data' => [
                'type' => 'orders',
                'attributes' => [
                    'customer' => [
                        'name' => $data['customer_name'],
                        'nif' => $data['customer_nif'],
                    ],
                    'summary' => [
                        'currency' => $data['currency'],
                        'total' => $data['total'],
                    ],
                    'lines' => $data['items'],
                ],
            ],
        ];

    }
}
