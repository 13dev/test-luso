<?php

namespace App\Infrastructure\Mappers;

use App\Infrastructure\ExternalOrderApi\DTOs\ExternalOrderV1RequestData;
use Spatie\LaravelData\Contracts\BaseData;
use Spatie\LaravelData\Data;

class OrderApiDataMapper
{
    public static function map(BaseData $externalData, string $version): Data
    {
        return match ($version) {
            'v1' => self::mapToV1($externalData),
            //'v2' => $this->mapToV2($externalData),
            default => throw new \InvalidArgumentException("Unsupported version {$version}"),
        };
    }

    protected static function mapToV1(BaseData $data): Data
    {
        return ExternalOrderV1RequestData::from([
            'customer_name' => $data->customer_name ?? '',
            'customer_nif' => $data->customer_nif ?? '',
            'total' => (float) ($data->total ?? 0),
            'items' => $data->items ?? [],
            'currency' => $data->currency,
        ]);

    }

}
