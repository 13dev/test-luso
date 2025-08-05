<?php

namespace App\Domain\Customer;

use App\Domain\Order\Order;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $attributes = [
        'id',
        'tax_id',
        'name',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
