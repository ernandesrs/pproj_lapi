<?php

namespace App\Http\Middleware;

use App\Exceptions\AppDemoException;
use Closure;
use Illuminate\Http\Request;

class AppDemoDisableResource
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
        if (env('APP_IS_DEMO')) {
            throw new AppDemoException();
        }

        return $next($request);
    }
}