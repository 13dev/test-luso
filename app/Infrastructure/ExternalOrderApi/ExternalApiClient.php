<?php

namespace App\Infrastructure\ExternalOrderApi;

use App\Application\Contracts\ExternalOrderApiInterface;
use App\Domain\Exceptions\ExternalOrderApiRequestFailedException;
use Illuminate\Support\Facades\Http;
use League\Uri\Contracts\UriInterface;
use Spatie\LaravelData\Data;

final class ExternalApiClient implements ExternalOrderApiInterface
{
    public function __construct(
        /** @var Data */
        private readonly string $requestResource,

        /** @var Data */
        private readonly string $responseResource,

        private readonly UriInterface $url,
    ) {
    }

    /**
     * @throws ExternalOrderApiRequestFailedException
     */
    public function call(array $data): \Spatie\LaravelData\Contracts\BaseData
    {

        dd($this->requestResource::from($data)->toArray());

        $response = Http::withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
            ->post(
                url: $this->url->toString(),
                data: $this->requestResource::from($data)->toArray(),
            );

        if ($response->failed()) {
            throw ExternalOrderApiRequestFailedException::fromHttpResponse(
                message: 'Failed to send order to the external API',
                request: $this->requestResource::from($data)->toArray(),
                url: $this->url->toString(),
                response: $response,
            );
        }

        return $this->responseResource::from($response);

    }

}
