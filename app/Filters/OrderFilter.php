<?php

namespace App\Filters;

use App\Filters\Components\Product;
use App\Filters\Components\Status;
use App\Filters\Components\Variation;

class OrderFilter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            Status::class,
            Product::class,
        ];
    }
}
