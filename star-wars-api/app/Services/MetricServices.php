<?php

namespace App\Services;

use App\Data\ProcessRequestData;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
readonly class MetricServices
{
    private string $stats_key;
    private string $latency_key;

    public function __construct()
    {
        $this->stats_key = 'api:stats';
        $this->latency_key = 'api:latency';
    }
    public function save(
        ProcessRequestData $processRequestData,
    ): void
    {
        try {
            Log::info('MetricServices::save', $processRequestData->toArray());
            $minute = now()->timezone('America/Sao_Paulo')->format('Y-m-d-H-i');
            Log::info('MetricServices::save', ['hour' => $minute]);
            $route_method_key = "{$processRequestData->route}:{$processRequestData->method}";
            $stats_key = "{$this->stats_key}:{$route_method_key}:{$minute}";
            $stats_key_duration_sum = "{$this->stats_key}:{$processRequestData->route}:{$processRequestData->method}:{$minute}:duration_sum";
            Redis::incr($stats_key);
            Redis::connection()->incrbyfloat($stats_key_duration_sum, $processRequestData->duration);
            Redis::expire($stats_key, 7200);
            Redis::expire($stats_key_duration_sum, 7200);
            $latency_key = "{$this->latency_key}:{$route_method_key}:{$minute}";
            Redis::rpush($latency_key, (string) $processRequestData->duration);
            Redis::expire($latency_key, 7200);
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
        }
    }
}
