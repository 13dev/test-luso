<?php

namespace App\Infrastructure\DTOs;

use App\Domain\Order\Casts\CustomerCastAndTransformer;
use App\Domain\Order\Casts\MoneyCastAndTransformer;
use App\Domain\Order\ValueObjects\CustomerValueObject;
use App\Domain\Order\ValueObjects\MoneyValueObject;
use App\Infrastructure\ExternalOrderApi\DTOs\ExternalOrderItemData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\ListType;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Size;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\WithCastAndTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class InternalOrderData extends Data
{
    public function __construct(
        #[Required, WithCastAndTransformer(CustomerCastAndTransformer::class)]
        public CustomerValueObject $customer,

        #[Required, WithCastAndTransformer(MoneyCastAndTransformer::class)]
        public MoneyValueObject $total,

        #[Required, DataCollectionOf(ExternalOrderItemData::class)]
        public DataCollection $items
    )
    {

    }
}
