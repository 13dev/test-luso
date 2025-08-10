<?php

namespace Tests\Unit\Domain\Order\ValueObjects;

use App\Domain\Order\ValueObjects\TaxIdValueObject;
use RuntimeException;
use Stringable;

it('can be created with a valid tax id', function () {
    $taxId = new TaxIdValueObject('123456789');

    expect((string) $taxId)->toBe('123456789');
});

it('throws exception for invalid length', function () {
    expect(fn () => new TaxIdValueObject('12345'))
        ->toThrow(RuntimeException::class, 'Invalid tax id value');
});

it('throws exception for non-numeric tax id', function () {
    expect(fn () => new TaxIdValueObject('ABC123XYZ'))
        ->toThrow(RuntimeException::class, 'Invalid tax id value');
});

it('is immutable and always stringable', function () {
    $taxId = new TaxIdValueObject('987654321');

    expect($taxId)->toBeInstanceOf(Stringable::class)
        ->and((string) $taxId)->toBe('987654321');
});
