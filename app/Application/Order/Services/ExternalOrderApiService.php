<?php

declare(strict_types=1);

namespace App\Application\Order\Services;

use App\Domain\Exceptions\ExternalOrderApiRequestFailedException;
use App\Infrastructure\ExternalOrderApi\ExternalOrderApiFactory;
use Http;

class ExternalOrderApiService
{
    /**
     * @throws ExternalOrderApiRequestFailedException
     */
    public function call(array $orderData, string $version): array
    {
        $externalOrderApi = ExternalOrderApiFactory::resolve($version);

        $response = Http::post(
            url: $externalOrderApi->getUri()->toString(),
            data: $externalOrderApi->preparePayload($orderData)
        );

        if ($response->failed()) {
            throw ExternalOrderApiRequestFailedException::fromHttpResponse(
                message: 'Failed to send order to the external API',
                request: $orderData,
                url: $externalOrderApi->getUri()->toString(),
                response: $response,
            );
        }

        return $externalOrderApi->parseResponse($response->json());
    }
}
