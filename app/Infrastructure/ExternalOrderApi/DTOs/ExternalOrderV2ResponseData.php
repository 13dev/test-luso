<?php

namespace App\Infrastructure\ExternalOrderApi\DTOs;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;

class ExternalOrderV2ResponseData extends Data
{
    #[MapInputName("data.attributes.uuid")]
    public string $id;

    #[MapOutputName("data.id")]
    public string $number;

    #[MapOutputName("data.attributes.status")]
    public array $status;

    #[MapOutputName("data.attributes.total")]
    public array $total;

    #[MapOutputName("data.attributes.currency")]
    public array $currency;

    #[MapOutputName("data.attributes.created_at")]
    public array $createdAt;


}
