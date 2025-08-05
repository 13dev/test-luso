<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalOrderApi;

use App\Application\Contracts\ExternalOrderApiInterface;
use App\Infrastructure\DTOs\InternalOrderData;
use App\Infrastructure\ExternalOrderApi\DTOs\ExternalOrderV1RequestData;
use App\Infrastructure\ExternalOrderApi\DTOs\ExternalOrderV1ResponseData;
use App\Infrastructure\ExternalOrderApi\DTOs\ExternalOrderV2RequestData;
use App\Infrastructure\ExternalOrderApi\DTOs\ExternalOrderV2ResponseData;
use App\Infrastructure\Mappers\OrderApiDataMapper;
use InvalidArgumentException;
use League\Uri\Uri;

class ExternalOrderApiFactory
{
    public static function resolve(string $version): ExternalOrderApiInterface
    {
        return match ($version) {
            'v1' => new ExternalApiClient(
                ExternalOrderV1ResponseData::class,
                Uri::new(config('external_orders.v1.uri'))
            ),
            'v2' => new ExternalApiClient(
                ExternalOrderV2ResponseData::class,
                Uri::new(config('external_orders.v2.uri'))
            ),
            default => throw new InvalidArgumentException("Unsupported API version: $version")
        };

    }
}
