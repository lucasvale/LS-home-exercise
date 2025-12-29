<?php

namespace App\Services;

use App\Data\ProcessRequestData;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
readonly class MetricServices
{
    private string $stats_key;
    private string $latency_key;
    private string $search_term_stats_key;
    private string $search_term_latency_key;

    public function __construct()
    {
        $this->stats_key = 'api:stats';
        $this->latency_key = 'api:latency';
        $this->search_term_stats_key = 'api:search_term:stats';
        $this->search_term_latency_key = 'api:search_term:latency';
    }
    public function save(
        ProcessRequestData $processRequestData,
    ): void
    {
        try {

            Log::info('MetricServices::save', $processRequestData->toArray());
            $minute = now()->timezone(config('app.timezone'))->format('Y-m-d-H-i');
            $route_method_key = "{$processRequestData->route}:{$processRequestData->method}";
            if (!empty($processRequestData->search_term)) {
                $search_term_stats_key = "{$this->search_term_stats_key}:{$processRequestData->route}:{$processRequestData->method}:{$processRequestData->search_term}:{$minute}";
                $search_term_duration_sum_key = "{$this->search_term_stats_key}:{$processRequestData->route}:{$processRequestData->method}:{$processRequestData->search_term}:{$minute}:duration_sum";
                Redis::incr($search_term_stats_key);
                Redis::connection()->incrbyfloat($search_term_duration_sum_key, $processRequestData->duration);
                Redis::expire($search_term_stats_key, 600);
                Redis::expire($search_term_duration_sum_key, 600);
                $search_term_latency_key = "{$this->search_term_latency_key}:{$processRequestData->route}:{$processRequestData->method}:{$processRequestData->search_term}:{$minute}";
                Redis::rpush($search_term_latency_key, (string) $processRequestData->duration);
                Redis::expire($search_term_latency_key, 600);
                return;
            }
            $stats_key = "{$this->stats_key}:{$route_method_key}:{$minute}";
            $stats_key_duration_sum = "{$this->stats_key}:{$processRequestData->route}:{$processRequestData->method}:{$minute}:duration_sum";
            Redis::incr($stats_key);
            Redis::connection()->incrbyfloat($stats_key_duration_sum, $processRequestData->duration);
            Redis::expire($stats_key, 600);
            Redis::expire($stats_key_duration_sum, 600);
            $latency_key = "{$this->latency_key}:{$route_method_key}:{$minute}";
            Redis::rpush($latency_key, (string) $processRequestData->duration);
            Redis::expire($latency_key, 600);


        }catch (\Exception $exception){
            Log::error($exception->getMessage());
        }
    }
}
