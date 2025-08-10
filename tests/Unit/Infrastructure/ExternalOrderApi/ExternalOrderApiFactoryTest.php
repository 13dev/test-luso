<?php

namespace Tests\Unit\Infrastructure\ExternalOrderApi;

use App\Infrastructure\ExternalOrderApi\ExternalApiClient;
use App\Infrastructure\ExternalOrderApi\ExternalOrderApiFactory;
use App\Infrastructure\ExternalOrderApi\DTOs\ExternalOrderV1ResponseData;
use App\Infrastructure\ExternalOrderApi\DTOs\ExternalOrderV2ResponseData;
use App\Application\Contracts\ExternalOrderApiInterface;
use InvalidArgumentException;
use League\Uri\Uri;
use ReflectionClass;

beforeEach(function () {
    config()->set('external_orders.v1.uri', 'https://example.com/v1/orders');
    app('config')->set('external_orders.v2.uri', 'https://example.com/v2/orders');
});
it('resolves v1 API client with correct response resource', function () {
    $client = ExternalOrderApiFactory::resolve('v1');

    expect($client)->toBeInstanceOf(ExternalApiClient::class)
        ->and($client)->toBeInstanceOf(ExternalOrderApiInterface::class);


    // Using reflection to check private property
    $reflection = new ReflectionClass($client);
    $property = $reflection->getProperty('responseResource');
    $property->setAccessible(true);
    expect($property->getValue($client))->toBe(ExternalOrderV1ResponseData::class);
});

it('resolves v2 API client with correct response resource', function () {
    $client = ExternalOrderApiFactory::resolve('v2');

    expect($client)->toBeInstanceOf(ExternalApiClient::class)
        ->and($client)->toBeInstanceOf(ExternalOrderApiInterface::class);

    $reflection = new ReflectionClass($client);
    $property = $reflection->getProperty('responseResource');
    $property->setAccessible(true);
    expect($property->getValue($client))->toBe(ExternalOrderV2ResponseData::class);
});

it('throws exception for unsupported version', function () {
    expect(fn () => ExternalOrderApiFactory::resolve('v3'))
        ->toThrow(InvalidArgumentException::class, 'Unsupported API version: v3');
});
