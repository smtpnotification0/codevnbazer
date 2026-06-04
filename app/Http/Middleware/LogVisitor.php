<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Cache;

class LogVisitor
{
    public function handle($request, Closure $next)
    {
        $ipAddress = $request->ip();
        $today = today()->toDateString();
        $cacheKey = "visitor:$ipAddress:$today";

        if (!Cache::has($cacheKey)) {
            DB::table('visitors')->updateOrInsert(
                ['ip_address' => $ipAddress, 'visited_at' => $today],
                ['ip_address' => $ipAddress, 'visited_at' => $today]
            );

            Cache::put($cacheKey, true, now()->endOfDay());
        }

        return $next($request);
    }
}
