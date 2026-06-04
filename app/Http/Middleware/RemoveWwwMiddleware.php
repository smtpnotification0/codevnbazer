<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RemoveWwwMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (str_starts_with($request->getHost(), 'www.')) {
            $request->headers->set('Host', substr($request->getHost(), 4));

            return redirect()->to($request->getUri(), 301);
        }

        return $next($request);
    }
}
