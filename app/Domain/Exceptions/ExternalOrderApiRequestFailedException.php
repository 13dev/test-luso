<?php

namespace App\Domain\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

class ExternalOrderApiRequestFailedException extends Exception
{
    protected array $requestPayload = [];
    protected ?string $url = null;
    protected array $responseBody = [];
    protected int $statusCode = 0;

    public static function make(
        string $message,
        array $requestPayload = [],
        ?string $url = null,
        array $responseBody = [],
        int $statusCode = 0,
        ?Exception $previous = null
    ): self {
        $e = new self($message, $statusCode, $previous);
        $e->requestPayload = $requestPayload;
        $e->url = $url;
        $e->responseBody = $responseBody;
        $e->statusCode = $statusCode;

        return $e;
    }

    public function context(): array
    {
        return [
            'url' => $this->url,
            'request_payload' => $this->requestPayload,
            'response_body' => $this->responseBody,
            'status_code' => $this->statusCode,
        ];
    }


    public static function fromHttpResponse(string $message, array $request, string $url, Response $response): self
    {
        return self::make(
            $message,
            $request,
            $url,
            $response->json() ?? [],
            $response->status()
        );
    }
}
