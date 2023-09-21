<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('x-api-key');


        if(!$key || $key != config('service.api_key')){
            return \Illuminate\Support\Facades\Response::json([
                'message' => 'Missing or Invalid API key.'
            ], 400);
        }

        return $next($request);
    }
}
