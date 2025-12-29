<?php

namespace App\Http\Controllers;

use App\Dao\AggregatedStatsDao;
use App\Data\AggregatedStatsResponse;
use App\Services\AggregatedStatsService;
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
        $mostSearchedTerms = $this->statsDao->getMostSearchedTerms();

        $response = AggregatedStatsResponse::fromDaoResults(
            $peakHour,
            $averageResponseTime,
            $hourlyReport,
            $routes,
            $mostSearchedTerms
        );

        return response()->json($response->toArray());
    }
}
