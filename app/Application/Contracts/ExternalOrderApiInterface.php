<?php

declare(strict_types=1);


namespace App\Application\Contracts;

use Spatie\LaravelData\Contracts\BaseData;

interface ExternalOrderApiInterface
{
    public function call(array $data): BaseData;

}
