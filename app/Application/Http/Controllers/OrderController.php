<?php

declare(strict_types=1);

namespace App\Application\Http\Controllers;

use App\Application\VersionResolver;
use App\Domain\Exceptions\ExternalOrderApiRequestFailedException;
use App\Domain\Order\AggregateRoots\OrderAggregateRoot;
use App\Infrastructure\DTOs\InternalOrderData;
use App\Infrastructure\ExternalOrderApi\ExternalOrderApiFactory;
use App\Infrastructure\Mappers\OrderApiDataMapper;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function __construct(private readonly VersionResolver $versionResolver)
    {
    }

    /**
     * @throws ExternalOrderApiRequestFailedException
     */
    public function store(InternalOrderData $data)
    {
        $version = $this->versionResolver->resolve();

        $mapper = OrderApiDataMapper::map($data, $version);


        $externalOrders = ExternalOrderApiFactory::resolve($version)->call($mapper);

        OrderAggregateRoot::retrieve($externalOrders->id)
            ->create(customer: $data->customer, total: $data->total, items: $data->items->toArray());

        return $externalOrders;

    }
}
