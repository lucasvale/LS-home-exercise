<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class MostClickedRouteData extends Data
{
    public function __construct(
        public string $route,
        public string $method,
        public int $total_clicks,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            route: $data['route'] ?? '',
            method: $data['method'] ?? '',
            total_clicks: (int) ($data['total_clicks'] ?? 0),
        );
    }
}
