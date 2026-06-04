<?php

namespace App\Filters\Components;

use Closure;

class Product implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['title'])) {
            $value = $content['params']['title'];
            $content['builder']->where(function ($query) use ($value) {
                $query->whereHas('product', function ($query) use ($value) {
                    $query->where('title', 'LIKE', "%{$value}%");
                })
                    ->orWhereHas('variation', function ($query) use ($value) {
                        $query->where('title', 'LIKE', "%{$value}%");
                    });
            });
        }

        return $next($content);
    }
}
