<?php

namespace App\Infrastructure\ExternalOrderApi\DTOs;

use Spatie\LaravelData\Data;

class ExternalOrderV1ResponseData extends Data
{
    public function __construct(
        public string $number,
        public string $uuid
    ) {}


    public static function from(...$payload): static
    {
        // If API response wraps the object in a "data" key, unwrap it
        if (isset($payload['data']) && is_array($payload['data'])) {
            $payload = $payload['data'];
        }

        return parent::from($payload);
    }

}
