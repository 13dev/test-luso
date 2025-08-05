<?php

declare(strict_types=1);

namespace App\Domain\Order\Casts;

use App\Domain\Order\ValueObjects\MoneyValueObject;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class MoneyCastAndTransformer implements Cast, Transformer
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): MoneyValueObject
    {
        if (is_array($value)) {
            return MoneyValueObject::from($value['amount'], $value['currency']);
        }

        return MoneyValueObject::from($value);
    }

    public function transform(DataProperty $property, $value, TransformationContext $context): array
    {
        return [
            'currency' => $value->getCurrency(),
            'amount' => $value->getAmount(),
        ];
    }
}
