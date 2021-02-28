<?php

namespace App\Http\Middleware;

use Closure;
use Log;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Max-Age' => 3600,
            'Access-Control-Allow-Methods' => 'GET, PUT, POST, DELETE, HEAD, OPTIONS',
            'Access-Control-Allow-Headers' => '*',
        ];

        if ($request->getMethod() == "OPTIONS")
        {
            return response('')->withHeaders($headers);
        }
        return $next($request)->withHeaders($headers);
    }
}
