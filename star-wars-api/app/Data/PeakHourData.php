<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class PeakHourData extends Data
{
    public function __construct(
        public ?int $hour,
        public int $total_requests,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            hour: $data['hour'] ?? null,
            total_requests: $data['total_requests'] ?? 0,
        );
    }
}
