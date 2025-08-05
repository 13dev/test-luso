<?php

namespace App\Infrastructure\ExternalOrderApi;

use App\Application\Contracts\ExternalOrderApiInterface;
use App\Domain\Exceptions\ExternalOrderApiRequestFailedException;
use Illuminate\Support\Facades\Http;
use League\Uri\Contracts\UriInterface;
use Spatie\LaravelData\Contracts\BaseData;
use Spatie\LaravelData\Data;

final class ExternalApiClient implements ExternalOrderApiInterface
{
    public function __construct(
        /** @var Data */
        private readonly string $responseResource,

        private readonly UriInterface $url,

    ) {
    }

    /**
     * @throws ExternalOrderApiRequestFailedException
     */
    public function call(array $data): BaseData
    {
        $response = Http::withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
            ->post(
                url: $this->url->toString(),
                data: $data,
            );

        if ($response->failed()) {
            throw ExternalOrderApiRequestFailedException::fromHttpResponse(
                message: 'Failed to send order to the external API',
                request: $data,
                url: $this->url->toString(),
                response: $response,
            );
        }


        dd($response->json());
        return $this->responseResource::from($response->json());

    }

}
