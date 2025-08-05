<?php

namespace App\Infrastructure\ExternalOrderApi\DTOs;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class ExternalOrderV2ResponseData extends Data
{

    public function __construct(
        #[MapInputName("data.id")]
        public string $id,

        #[MapInputName("data.attributes.uuid")]
        public string $uuid
    ) {

    }

}
