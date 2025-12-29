<?php

namespace App\Dao;

class AggregatedStatsDao extends BaseDao
{

    public function getAggregatedStats()
    {
        $this->query = "SELECT * from aggregated_stats";
        return $this->executeQuery();
    }

    public function saveAggregatedStats(array $stats): void
    {
        $this->query = "INSERT INTO aggregated_stats
            (route, method, measure_date, date_interval, duration_sum, ticks, average_response_time, search_term)
            VALUES
            (:route, :method, :measure_date, :date_interval, :duration_sum, :ticks, :average_response_time, :search_term)";"
            ON DUPLICATE KEY UPDATE date_interval = :date_interval,
                                    duration_sum = :duration_sum,
                                    ticks = :ticks,
                                    average_response_time = :average_response_time
            ";

        $this->bindings = [
            ':route' => $stats['route'],
            ':method' => $stats['method'],
            ':measure_date' => $stats['measure_date'],
            ':date_interval' => $stats['date_interval'],
            ':duration_sum' => $stats['duration_sum'],
            ':ticks' => $stats['ticks'],
            ':average_response_time' => $stats['average_response_time'],
            ':search_term' => $stats['search_term'] ?? ''
        ];

        $this->executeQuery();
    }

    public function getPeakHour(): array
    {
        $this->query = "
            SELECT
                HOUR(measure_date) as hour,
                SUM(ticks) as total_requests
            FROM aggregated_stats
            WHERE DATE(measure_date) = CURDATE()
            GROUP BY HOUR(measure_date)
            ORDER BY total_requests DESC
            LIMIT 1
        ";

        $result = $this->executeQuery();
        return $result[0] ?? [];
    }

    public function getAverageResponseTime(): array
    {
        $this->query = "
            SELECT
                SUM(duration_sum) / SUM(ticks) as average_response_time
            FROM aggregated_stats
            WHERE DATE(measure_date) = CURDATE()
            AND ticks > 0
        ";

        $result = $this->executeQuery();
        return $result[0] ?? [];
    }

    public function getAggregatedStatsReport(): array
    {
        $this->query = "
            SELECT
                HOUR(measure_date) as hour,
                SUM(ticks) as total_requests,
                SUM(duration_sum) as total_duration,
                AVG(average_response_time) as avg_response_time
            FROM aggregated_stats
            WHERE DATE(measure_date) = CURDATE()
            GROUP BY HOUR(measure_date)
            ORDER BY hour ASC
        ";

        return $this->executeQuery();
    }

    public function getMostClickedRoutes(int $limit = 10): array
    {
        $this->query = "
            SELECT
                route,
                method,
                SUM(ticks) as total_clicks
            FROM aggregated_stats
            GROUP BY route, method
            ORDER BY total_clicks DESC
            LIMIT :limit
        ";

        $this->bindings = [':limit' => $limit];

        return $this->executeQuery();
    }

    public function getMostSearchedTerms(int $limit = 10): array
    {
        $this->query = "
            SELECT
                search_term,
                SUM(ticks) as total_searches
            FROM aggregated_stats
            WHERE search_term IS NOT NULL AND search_term != ''
            GROUP BY search_term
            ORDER BY total_searches DESC
            LIMIT :limit
        ";

        $this->bindings = [':limit' => $limit];

        return $this->executeQuery();
    }
}
