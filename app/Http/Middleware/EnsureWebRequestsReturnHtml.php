<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureWebRequestsReturnHtml
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
    $response = $next($request);

        if ($response->headers->get('Content-Type') === 'application/json' && !$request->expectsJson()) {
            $response->headers->set('Content-Type', 'text/html');
        }

        return $response;
    }
}
