<?php

namespace App\Services;

use App\Dao\AggregatedStatsDao;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class AggregatedStatsService
{

    private string $stats_key = 'api:stats';
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

    public function processBackgroundTask():void
    {
        try {
            Log::info('ProcessAggregatedStats: Starting aggregation process');

            $timestamps = $this->getLast5MinutesTimestamps();

            Log::info('ProcessAggregatedStats: Timestamps to process', ['timestamps' => $timestamps]);

            $aggregatedData = [];

            foreach ($timestamps as $timestamp) {
                $pattern = "{$this->stats_key}:*:{$timestamp}";
                $keys = Redis::keys($pattern);

                Log::info('ProcessAggregatedStats: Keys found', [
                    'keys' => $keys,
                    'timestamp' => $timestamp,
                    'pattern' => $pattern,
                    'keys_count' => count($keys)
                ]);

                foreach ($keys as $keyWithPrefix) {
                    if (str_ends_with($keyWithPrefix, ':duration_sum')) {
                        continue;
                    }

                    $key = $this->removeRedisPrefix($keyWithPrefix);

                    $parts = explode(':', $key);
                    if (count($parts) >= 5) {
                        $method = $parts[count($parts) - 2];
                        $route = implode(':', array_slice($parts, 2, count($parts) - 4));

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

                        if (!isset($aggregatedData[$routeMethodKey])) {
                            $aggregatedData[$routeMethodKey] = [
                                'route' => $route,
                                'method' => $method,
                                'ticks' => 0,
                                'duration_sum' => 0.0,
                            ];
                        }

                        $aggregatedData[$routeMethodKey]['ticks'] += $count;
                        $aggregatedData[$routeMethodKey]['duration_sum'] += $durationSum;
                    }
                }
            }
            $measureDate = now()->timezone('America/Sao_Paulo')->subMinutes(5)->startOfMinute();

            Log::info('ProcessAggregatedStats: Aggregated data before saving', [
                'aggregated_data' => $aggregatedData
            ]);

            foreach ($aggregatedData as $data) {
                $averageResponseTime = $data['ticks'] > 0
                    ? $data['duration_sum'] / $data['ticks']
                    : 0.0;

                $stats = [
                    'route' => $data['route'],
                    'method' => $data['method'],
                    'measure_date' => $measureDate->format('Y-m-d H:i:s'),
                    'date_interval' => '5min',
                    'duration_sum' => $data['duration_sum'],
                    'ticks' => $data['ticks'],
                    'average_response_time' => $averageResponseTime,
                ];

                $this->aggregatedStatsDao->saveAggregatedStats($stats);

                Log::info('ProcessAggregatedStats: Saved aggregated stats', $stats);
            }

            Log::info('ProcessAggregatedStats: Aggregation completed', [
                'routes_processed' => count($aggregatedData)
            ]);

        } catch (\Exception $e) {
            Log::error('ProcessAggregatedStats: Error processing aggregated stats', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

    }

    /**
     * Get last 5 minutes timestamps in Y-m-d-h-m format
     */
    private function getLast5MinutesTimestamps(): array
    {
        $timestamps = [];
        $now = now()->timezone('America/Sao_Paulo');

        for ($i = 1; $i <= 5; $i++) {
            $timestamps[] = $now->copy()->subMinutes($i)->format('Y-m-d-H-i');
        }

        return $timestamps;
    }

}
