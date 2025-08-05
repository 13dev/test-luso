<?php

declare(strict_types=1);

namespace App\Application;

use Illuminate\Http\Request;

final class VersionResolver
{
    private string $defaultVersion = 'v1';
    private const ORDER_VERSION_HEADER = 'X-Order-Version';

    public function __construct(
        private readonly Request $request
    ) {
    }

    public function resolve(): string
    {
        // from custom header
        if ($this->request->hasHeader(self::ORDER_VERSION_HEADER)) {
            return strtolower($this->request->header(self::ORDER_VERSION_HEADER)) === $this->defaultVersion ? 'v1' : 'v2';
        }

        // from payload structure (nested JSON:API)
        $data = $this->request->json('data', null);

        if (is_array($data) && ($data['type'] ?? null) === 'orders') {
            return 'v2';
        }

        return $this->defaultVersion;
    }
}
