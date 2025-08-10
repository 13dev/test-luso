<?php

namespace Tests\Feature;

use App\Domain\Exceptions\ExternalOrderApiRequestFailedException;
use App\Infrastructure\ExternalOrderApi\DTOs\ExternalOrderV1ResponseData;
use App\Infrastructure\ExternalOrderApi\ExternalApiClient;
use Illuminate\Support\Facades\Http;
use League\Uri\Uri;

it('makes a successful POST request to the external API', function () {
    Http::fake([
        'https://fake-api.test/v1' => Http::response(['success' => true], 200),
    ]);

    $client = new ExternalApiClient(
        responseResource: ExternalOrderV1ResponseData::class,
        url: Uri::new('https://fake-api.test/v1')
    );

    $response = $client->call(['order' => 'data']);

    expect($response)->toBeInstanceOf(ExternalOrderV1ResponseData::class);
});


it('throws ExternalOrderApiRequestFailedException on failed API call', function () {
    Http::fake([
        'https://fake-api.test/v1' => Http::response(['error' => 'Unauthorized'], 401),
    ]);

    $client = new ExternalApiClient(
        responseResource: ExternalOrderV1ResponseData::class,
        url: Uri::new('https://fake-api.test/v1')
    );

    $this->expectException(ExternalOrderApiRequestFailedException::class);

    $client->call(['order' => 'data']);
});
