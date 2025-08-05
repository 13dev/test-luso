<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalOrderApi;

use App\Application\Contracts\ExternalOrderApiInterface;
use App\Infrastructure\ExternalOrderApi\Resources\ExternalOrderV1RequestResource;
use App\Infrastructure\ExternalOrderApi\Resources\ExternalOrderV1ResponseResource;
use App\Infrastructure\ExternalOrderApi\Resources\ExternalOrderV2RequestResource;
use App\Infrastructure\ExternalOrderApi\Resources\ExternalOrderV2ResponseResource;
use InvalidArgumentException;

class ExternalOrderApiFactory
{
    public static function resolve(string $version): ExternalOrderApiInterface
    {
        return match ($version) {
            'v1' => new ExternalApiClient(
                ExternalOrderV1RequestResource::class,
                ExternalOrderV1ResponseResource::class,
                config('external_orders.v1.uri')
            ),
            'v2' => new ExternalApiClient(
                ExternalOrderV2RequestResource::class,
                ExternalOrderV2ResponseResource::class,
                config('external_orders.v2.uri')
            ),
            default => throw new InvalidArgumentException("Unsupported API version: $version")
        };

    }
}
