<?php

namespace Tests\Unit\Domain\Order\ValueObjects;

use App\Domain\Order\ValueObjects\MoneyValueObject;
use Cknow\Money\Money;
use Money\Currency;

it('can be created from a float amount', function () {
    $vo = MoneyValueObject::from(123.45, 'EUR');

    expect($vo->money)->toBeInstanceOf(Money::class)
        ->and($vo->getAmount())->toBe('12345')
        ->and($vo->getCurrency())->toBeInstanceOf(Currency::class)
        ->and($vo->getCurrency()->getCode())->toBe('EUR');
});

it('can be created from a string amount', function () {
    $vo = MoneyValueObject::from('99.99', 'USD');

    expect($vo->getAmount())->toBe('9999')
        ->and($vo->getCurrency()->getCode())->toBe('USD');
});

it('returns the correct decimal representation', function () {
    $vo = MoneyValueObject::from(50, 'EUR');

    expect($vo->toDecimal())->toBe('50.00');
});

it('retains exact currency information', function () {
    $vo = MoneyValueObject::from(10, 'GBP');

    $currency = $vo->getCurrency();

    expect($currency)->toBeInstanceOf(Currency::class)
        ->and($currency->getCode())->toBe('GBP');
});
