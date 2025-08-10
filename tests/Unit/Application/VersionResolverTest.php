<?php

namespace Tests\Unit\Application;

use App\Application\VersionResolver;
use Illuminate\Http\Request;
use Mockery;

it('returns v1 when header matches default version', function () {
    $request = Mockery::mock(Request::class);
    $request->shouldReceive('hasHeader')
        ->once()
        ->with('X-Order-Version')
        ->andReturn(true);
    $request->shouldReceive('header')
        ->once()
        ->with('X-Order-Version')
        ->andReturn('v1');

    $resolver = new VersionResolver($request);

    expect($resolver->resolve())->toBe('v1');
});

it('returns v2 when header does not match default version', function () {
    $request = Mockery::mock(Request::class);
    $request->shouldReceive('hasHeader')
        ->once()
        ->with('X-Order-Version')
        ->andReturn(true);
    $request->shouldReceive('header')
        ->once()
        ->with('X-Order-Version')
        ->andReturn('v3');

    $resolver = new VersionResolver($request);

    expect($resolver->resolve())->toBe('v2');
});

it('returns v2 when payload contains orders type', function () {
    $request = Mockery::mock(Request::class);
    $request->shouldReceive('hasHeader')
        ->once()
        ->with('X-Order-Version')
        ->andReturn(false);
    $request->shouldReceive('json')
        ->once()
        ->with('data', null)
        ->andReturn(['type' => 'orders']);

    $resolver = new VersionResolver($request);

    expect($resolver->resolve())->toBe('v2');
});

it('returns v1 when payload type is not orders', function () {
    $request = Mockery::mock(Request::class);
    $request->shouldReceive('hasHeader')
        ->once()
        ->with('X-Order-Version')
        ->andReturn(false);
    $request->shouldReceive('json')
        ->once()
        ->with('data', null)
        ->andReturn(['type' => 'products']);

    $resolver = new VersionResolver($request);

    expect($resolver->resolve())->toBe('v1');
});

it('returns v1 when no header and no payload', function () {
    $request = Mockery::mock(Request::class);
    $request->shouldReceive('hasHeader')
        ->once()
        ->with('X-Order-Version')
        ->andReturn(false);
    $request->shouldReceive('json')
        ->once()
        ->with('data', null)
        ->andReturn(null);

    $resolver = new VersionResolver($request);

    expect($resolver->resolve())->toBe('v1');
});
