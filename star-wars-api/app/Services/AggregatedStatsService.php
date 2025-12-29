<?php

namespace App\Services;

use App\Dao\AggregatedStatsDao;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class AggregatedStatsService
{

    private string $stats_key = 'api:stats';
    private string $search_term_stats_key = 'api:search_term:stats';

    private string $redis_prefix;

    public function __construct(
        private readonly AggregatedStatsDao $aggregatedStatsDao
    )
    {
        $this->redis_prefix = config('database.redis.options.prefix', '');
    }

    /**
     * Remove Redis prefix from key
     */
    private function removeRedisPrefix(string $key): string
    {
        if (!empty($this->redis_prefix) && str_starts_with($key, $this->redis_prefix)) {
            return substr($key, strlen($this->redis_prefix));
        }
        return $key;
    }

    public function processBackgroundTask(): void
    {
        try {
            Log::info('ProcessAggregatedStats: Starting aggregation process');

            $timestamps = $this->getLast5MinutesTimestamps();
            $measureDate = Carbon::now()->setTimezone(config('app.timezone'));

            Log::info('ProcessAggregatedStats: Timestamps to process', ['timestamps' => $timestamps]);

            $termAggregatedData = [];
            $routeAggregatedData = [];

            foreach ($timestamps as $timestamp) {

                $route_pattern = "{$this->stats_key}:*:{$timestamp}";
                $route_keys = Redis::keys($route_pattern);

                Log::info('ProcessAggregatedStats: Keys found', [
                    'keys' => $route_keys,
                    'timestamp' => $timestamp,
                    'pattern' => $route_pattern,
                    'keys_count' => count($route_keys)
                ]);

                foreach ($route_keys as $keyWithPrefix) {
                    if (str_ends_with($keyWithPrefix, ':duration_sum')) {
                        continue;
                    }
                    $key = $this->removeRedisPrefix($keyWithPrefix);
                    $parts = explode(':', $key);
                    if (count($parts) >= 5) {
                        $method = $parts[count($parts) - 2];
                        $route = implode(':', array_slice($parts, 2, count($parts) - 4));
                        $measureDateString = $parts[4];
                        $measureDate = Carbon::createFromFormat('Y-m-d-H-i', $measureDateString)->setTimezone(config('app.timezone'));

                        $routeMethodKey = "{$route}:{$method}";

                        $count = (int)Redis::get($key) ?: 0;

                        $durationSumKey = "{$this->stats_key}:{$route}:{$method}:{$timestamp}:duration_sum";
                        $durationSum = (float)Redis::get($durationSumKey) ?: 0.0;

                        Log::info('ProcessAggregatedStats: Processing key', [
                            'key_with_prefix' => $keyWithPrefix,
                            'key_without_prefix' => $key,
                            'route' => $route,
                            'method' => $method,
                            'count' => $count,
                            'duration_sum_key' => $durationSumKey,
                            'duration_sum' => $durationSum
                        ]);

                        if (!isset($routeAggregatedData[$routeMethodKey])) {
                            $routeAggregatedData[$routeMethodKey] = [
                                'route' => $route,
                                'method' => $method,
                                'ticks' => 0,
                                'duration_sum' => 0.0,
                            ];
                        }

                        $routeAggregatedData[$routeMethodKey]['ticks'] += $count;
                        $routeAggregatedData[$routeMethodKey]['duration_sum'] += $durationSum;
                    }
                }
                $search_term_pattern = "{$this->search_term_stats_key}:*:{$timestamp}";
                $search_term_keys = Redis::keys($search_term_pattern);

                foreach ($search_term_keys as $keyWithPrefix) {
                    if (str_ends_with($keyWithPrefix, ':duration_sum')) {
                        continue;
                    }

                    $key = $this->removeRedisPrefix($keyWithPrefix);

                    $parts = explode(':', $key);

                    if (count($parts) >= 5) {
                        $route = $parts[3];
                        $method = $parts[4];
                        $search_term = $parts[5];
                        $count = (int)Redis::get($key) ?: 0;
                        $durationSumKey = "{$key}:duration_sum";
                        $durationSum = (float)Redis::get($durationSumKey) ?: 0.0;
                        $measureDateString = $parts[6];

                        $measureDate = Carbon::createFromFormat('Y-m-d-H-i', $measureDateString)->setTimezone(config('app.timezone'));
                        Log::info('ProcessAggregatedStats: Processing key', [
                            'key_with_prefix' => $keyWithPrefix,
                            'key_without_prefix' => $key,
                            'route' => $route,
                            'method' => $method,
                            'count' => $count,
                            'term' => $search_term,
                            'duration_sum_key' => $durationSumKey,
                            'duration_sum' => $durationSum
                        ]);
                        $routeMethodKey = "{$route}:{$method}";

                        if (!isset($termAggregatedData[$routeMethodKey])) {
                            $termAggregatedData[$routeMethodKey] = [
                                'route' => $route,
                                'method' => $method,
                                'ticks' => 0,
                                'duration_sum' => 0.0,
                                'search_term' => $search_term,
                            ];
                        }

                        $termAggregatedData[$routeMethodKey]['ticks'] += $count;
                        $termAggregatedData[$routeMethodKey]['duration_sum'] += $durationSum;
                    }
                }

            }

            Log::info('ProcessAggregatedStats: Aggregated data before saving', [
                'aggregated_data' => $routeAggregatedData
            ]);

            $this->saveData($routeAggregatedData, $measureDate);
            $this->saveData($termAggregatedData, $measureDate);

            Log::info('ProcessAggregatedStats: Aggregation completed', [
                'routes_processed' => count($termAggregatedData)
            ]);

        } catch (\Exception $e) {
            Log::error('ProcessAggregatedStats: Error processing aggregated stats', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

    }

    private function saveData( array $aggregatedData, $measureDate): void
    {
        foreach ($aggregatedData as $data) {
            $averageResponseTime = $data['ticks'] > 0
                ? $data['duration_sum'] / $data['ticks']
                : 0.0;

            $stats = [
                'route' => $data['route'],
                'method' => $data['method'],
                'measure_date' => $measureDate?->format('Y-m-d H:i:s'),
                'date_interval' => '5min',
                'duration_sum' => $data['duration_sum'],
                'ticks' => $data['ticks'],
                'average_response_time' => $averageResponseTime,
                'search_term' => $data['search_term'] ?? '',
            ];

            Log::info('ProcessAggregatedStats: Saved aggregated stats', $stats);
            try {
                $this->aggregatedStatsDao->saveAggregatedStats($stats);
            } catch (\Exception $exception){}
        }


    }

    /**
     * Get last 5 minutes timestamps in Y-m-d-h-m format
     */
    private function getLast5MinutesTimestamps(): array
    {
        $timestamps = [];
        $now = now()->timezone(config('app.timezone'));

        for ($i = 1; $i <= 5; $i++) {
            $timestamps[] = $now->copy()->subMinutes($i)->format('Y-m-d-H-i');
        }

        return $timestamps;
    }

}
