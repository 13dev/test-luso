<?php

declare(strict_types=1);

namespace App\Infrastructure\DTOs;

use App\Domain\Order\Casts\CustomerCastAndTransformer;
use App\Domain\Order\Casts\MoneyCastAndTransformer;
use App\Domain\Order\ValueObjects\CustomerValueObject;
use App\Domain\Order\ValueObjects\MoneyValueObject;
use App\Infrastructure\ExternalOrderApi\DTOs\ExternalOrderV1ItemData;
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
        #[Required, WithCastAndTransformer(CustomerCastAndTransformer::class, ['name' => 'customer.full_name', 'nif' => 'customer.nif'])]
        public CustomerValueObject $customer,
        #[Required, WithCastAndTransformer(MoneyCastAndTransformer::class)]
        public MoneyValueObject $total,
        #[Required, DataCollectionOf(ExternalOrderV1ItemData::class)]
        public DataCollection $items
    ) {

    }
}
