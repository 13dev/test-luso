<?php

declare(strict_types=1);

namespace App\Domain\Order\Casts;

use App\Domain\Order\ValueObjects\CustomerValueObject;
use App\Domain\Order\ValueObjects\TaxIdValueObject;
use Arr;
use Spatie\LaravelData\Attributes\WithCastAndTransformer;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class CustomerCastAndTransformer implements Cast, Transformer
{
    public function cast(
        DataProperty $property,
        mixed $value,
        array $properties,
        CreationContext $context
    ): CustomerValueObject {
        $props = $property->attributes->first(WithCastAndTransformer::class)->arguments[0] ?? $value;

        return new CustomerValueObject(Arr::get($properties, $props['name']), new TaxIdValueObject(Arr::get($properties, $props['nif'])));

    }

    public function transform(DataProperty $property, $value, TransformationContext $context): array
    {
        return [
            'name' => $value->name,
            'nif' => $value->nif,
        ];
    }
}
