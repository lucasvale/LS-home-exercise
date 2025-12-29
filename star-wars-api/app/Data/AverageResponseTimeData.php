<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class AverageResponseTimeData extends Data
{
    public function __construct(
        public float $time_seconds,
        public float $time_ms,
    ) {
    }

    public static function fromArray(array $data): self
    {
        $timeSeconds = $data['average_response_time'] ?? 0;

        return new self(
            time_seconds: $timeSeconds,
            time_ms: round($timeSeconds * 1000, 2),
        );
    }
}
