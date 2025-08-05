<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalOrderApi\DTOs;

use App\Domain\Order\Casts\MoneyCastAndTransformer;
use App\Domain\Order\ValueObjects\MoneyValueObject;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\WithCastAndTransformer;
use Spatie\LaravelData\Data;

class ExternalOrderV2ItemData extends Data
{
    public function __construct(
        #[Required, StringType]
        public string $sku,
        #[Required, IntegerType, Min(1)]
        public int $qty,
        #[Computed, WithCastAndTransformer(MoneyCastAndTransformer::class)]
        public MoneyValueObject $price,
    ) {
    }

}
