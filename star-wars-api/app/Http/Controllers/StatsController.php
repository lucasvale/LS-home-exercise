<?php

namespace App\Http\Controllers;

use App\Dao\AggregatedStatsDao;
use App\Services\AggregatedStatsService;
use App\Services\MetricServices;
use Symfony\Component\HttpFoundation\JsonResponse;

class StatsController extends Controller
{

    public function __construct(
        private readonly AggregatedStatsDao $statsDao,
        private readonly AggregatedStatsService $aggregatedStatsService
    )
    {
    }
    public function processStats(): void
    {
        $this->aggregatedStatsService->processBackgroundTask();
    }

    public function getAggregatedStats(): JsonResponse
    {
        $peakHour = $this->statsDao->getPeakHour();
        $averageResponseTime = $this->statsDao->getAverageResponseTime();
        $hourlyReport = $this->statsDao->getAggregatedStatsReport();
        $routes = $this->statsDao->getMostClickedRoutes();

        return response()->json([
            'peak_hour' => [
                'hour' => $peakHour['hour'] ?? null,
                'total_requests' => $peakHour['total_requests'] ?? 0
            ],
            'average_response_time' => [
                'time_seconds' => $averageResponseTime['average_response_time'] ?? 0,
                'time_ms' => isset($averageResponseTime['average_response_time'])
                    ? round($averageResponseTime['average_response_time'] * 1000, 2)
                    : 0,
            ],
            'hourly_breakdown' => $hourlyReport,
            'most_clicked_routes' => $routes
        ]);
    }
}
