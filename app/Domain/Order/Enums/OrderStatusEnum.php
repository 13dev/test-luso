<?php

declare(strict_types=1);

namespace App\Domain\Order\Enums;

enum OrderStatusEnum: string
{
    case Pending = 'pending';
    case Integrated = 'integrated';
    case Failed = 'failed';
    case Created = 'created';
}
