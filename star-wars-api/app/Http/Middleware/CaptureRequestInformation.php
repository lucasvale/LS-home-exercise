<?php

namespace App\Http\Middleware;

use App\Data\ProcessRequestData;
use App\Events\ProcessRequest;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CaptureRequestInformation
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $time_start = microtime(true);
        $response = $next($request);
        $duration = microtime(true) - $time_start;
        $method = $request->method();
        $status_code = $response->getStatusCode();
        $route = $request->route()->getName();
        Log::info('Info: ', [
            'method' => $method,
            'status_code' => $status_code,
            'route' => $route,
            'duration' => $duration,
        ]);
        ProcessRequest::dispatch(ProcessRequestData::from($method, $route, $duration, $status_code));
        return $response;
    }
}
