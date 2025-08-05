<?php

namespace App\Infrastructure\ExternalOrderApi\DTOs;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;

class ExternalOrderV1ResponseData extends Data
{

    #[MapInputName("data.uuid")]
    public string $id;

    #[MapOutputName("data.number")]
    public string $number;

    #[MapOutputName("data.status")]
    public array $status;

    #[MapOutputName("data.total")]
    public array $total;

    #[MapOutputName("data.currency")]
    public array $currency;

    #[MapOutputName("data.created_at")]
    public array $createdAt;

}
