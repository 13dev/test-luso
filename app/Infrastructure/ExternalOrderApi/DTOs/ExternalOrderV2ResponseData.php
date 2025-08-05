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

class ExternalOrderV2ResponseData extends Data
{
    #[MapInputName("data.id")]
    public string $id;

    #[MapInputName("data.attributes.status")]
    public string $status;

    #[MapInputName("data.attributes.summary.total")]
    public string $total;

    #[MapInputName("data.attributes.summary.currency")]
    public string $currency;

    #[MapInputName("data.attributes.lines"), DataCollectionOf(ExternalOrderV2ItemData::class)]
    public DataCollection $items;

    #[MapInputName("data.attributes.customer")]
    #[WithCastAndTransformer(CustomerCastAndTransformer::class, ['name' => 'data.attributes.customer.name', 'nif' => 'data.attributes.customer.nif'])]
    public CustomerValueObject $customer;

    #[MapInputName("data.attributes.created_at")]
    public string $createdAt;


}
