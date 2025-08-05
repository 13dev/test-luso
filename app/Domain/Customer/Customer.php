<?php

declare(strict_types=1);

namespace App\Domain\Customer;

use App\Domain\Order\Order;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'tax_id',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
