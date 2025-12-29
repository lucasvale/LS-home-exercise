<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class AggregatedStatsResponse extends Data
{
    public function __construct(
        public PeakHourData $peak_hour,
        public AverageResponseTimeData $average_response_time,
        /** @var HourlyBreakdownData[] */
        public array $hourly_breakdown,
        /** @var MostClickedRouteData[] */
        public array $most_clicked_routes,
        /** @var MostSearchedTermData[] */
        public array $most_searched_terms,
    ) {
    }

    public static function fromDaoResults(
        array $peakHour,
        array $averageResponseTime,
        array $hourlyReport,
        array $routes,
        array $mostSearchedTerms
    ): self {
        return new self(
            peak_hour: PeakHourData::fromArray($peakHour),
            average_response_time: AverageResponseTimeData::fromArray($averageResponseTime),
            hourly_breakdown: array_map(
                fn(array $item) => HourlyBreakdownData::fromArray($item),
                $hourlyReport
            ),
            most_clicked_routes: array_map(
                fn(array $item) => MostClickedRouteData::fromArray($item),
                $routes
            ),
            most_searched_terms: array_map(
                fn(array $item) => MostSearchedTermData::fromArray($item),
                $mostSearchedTerms
            ),
        );
    }
}
