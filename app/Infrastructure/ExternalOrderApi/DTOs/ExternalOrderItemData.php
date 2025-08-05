<?php

namespace App\Infrastructure\ExternalOrderApi\DTOs;

use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class ExternalOrderItemData extends Data
{
    public function __construct(
        #[Required, StringType]
        public string $sku,

        #[Required, IntegerType, Min(1)]
        public int $qty,

        #[Required, Numeric, Min(0)]
        public float $unit_price,
    ) {}
}
