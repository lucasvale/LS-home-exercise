<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class HourlyBreakdownData extends Data
{
    public function __construct(
        public int $hour,
        public int $total_requests,
        public float $total_duration,
        public float $avg_response_time,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            hour: (int) ($data['hour'] ?? 0),
            total_requests: (int) ($data['total_requests'] ?? 0),
            total_duration: (float) ($data['total_duration'] ?? 0),
            avg_response_time: (float) ($data['avg_response_time'] ?? 0),
        );
    }
}
