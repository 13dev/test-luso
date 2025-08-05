<?php

declare(strict_types=1);

namespace App\Domain\Order;

use App\Domain\Customer\Customer;
use App\Domain\Order\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $casts = [
        'items' => 'array',
        'total' => 'decimal:2',
        'status' => OrderStatusEnum::class,
    ];

    protected $fillable = [
        'uuid',
        'external_number',
        'external_uuid',
        'total',
        'items',
        'status',
        'customer_id'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id',
        'id');
    }
}
