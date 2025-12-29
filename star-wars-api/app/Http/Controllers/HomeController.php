<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use \Illuminate\Http\JsonResponse;

class HomeController extends Controller {
    public function index(): JsonResponse
    {
        $status = [
            'api' => 'ok',
            'database' => 'error',
            'redis' => 'error',
            'timestamp' => now()->toIso8601String(),
        ];

        // Check database connection
        try {
            DB::connection()->getPdo();
            $status['database'] = 'ok';
        } catch (\Exception $e) {
            $status['database_error'] = $e->getMessage();
        }

        // Check Redis connection
        try {
            Redis::ping();
            $status['redis'] = 'ok';
        } catch (\Exception $e) {
            $status['redis_error'] = $e->getMessage();
        }

        return response()->json($status);
    }
}
