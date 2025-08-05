<?php

declare(strict_types=1);


namespace App\Application\Contracts;

use Illuminate\Http\Resources\Json\JsonResource;
use League\Uri\Uri;

interface ExternalOrderApiInterface
{
    public function call(array $data): JsonResource;

}
