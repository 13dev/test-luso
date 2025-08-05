<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalOrderApi\DTOs;

use App\Domain\Order\Casts\CustomerCastAndTransformer;
use App\Domain\Order\ValueObjects\CustomerValueObject;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCastAndTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ExternalOrderV1ResponseData extends Data
{
    #[MapInputName("data.uuid")]
    public string $id;

    #[MapInputName("data.number")]
    public string $number;

    #[MapInputName("data.status")]
    public string $status;

    #[MapInputName("data.total")]
    public string $total;

    #[MapInputName("data.currency")]
    public string $currency;

    #[MapInputName("data.customer"), WithCastAndTransformer(CustomerCastAndTransformer::class, ['name' => 'data.customer_name', 'nif' => 'data.customer_nif'])]
    public CustomerValueObject $customer;

    #[MapInputName("data.attributes.lines"), DataCollectionOf(ExternalOrderV1ItemData::class)]
    public DataCollection $items;


}
