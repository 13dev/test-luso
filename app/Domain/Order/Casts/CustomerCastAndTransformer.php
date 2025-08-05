<?php

namespace App\Domain\Order\Casts;

use App\Domain\Order\ValueObjects\CustomerValueObject;
use App\Domain\Order\ValueObjects\TaxIdValueObject;
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

        return new CustomerValueObject($value['full_name'], new TaxIdValueObject($value['nif']));

    }

    public function transform(DataProperty $property, $value, TransformationContext $context): array
    {
        return [
            'name' => $value->name,
            'nif' => $value->nif,
        ];
    }
}
