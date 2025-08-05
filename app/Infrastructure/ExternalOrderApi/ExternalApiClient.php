<?php

namespace App\Infrastructure\ExternalOrderApi;

use App\Application\Contracts\ExternalOrderApiInterface;
use App\Domain\Exceptions\ExternalOrderApiRequestFailedException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Http;
use League\Uri\Contracts\UriInterface;

final class ExternalApiClient implements ExternalOrderApiInterface
{
    public function __construct(
        /** @var JsonResource */
        private readonly string $requestResource,

        /** @var JsonResource */
        private readonly string $responseResource,

        private readonly UriInterface $url,
    ) {
    }

    /**
     * @throws ExternalOrderApiRequestFailedException
     */
    public function call(array $data): JsonResource
    {
        $response = Http::post(
            url: $this->url->toString(),
            data: $this->requestResource::make($data),
        );

        if ($response->failed()) {
            throw ExternalOrderApiRequestFailedException::fromHttpResponse(
                message: 'Failed to send order to the external API',
                request: $data,
                url: $this->url->toString(),
                response: $response,
            );
        }

        return $this->responseResource::make($response);

    }

}
