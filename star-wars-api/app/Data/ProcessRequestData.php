<?php

namespace App\Data;

readonly class ProcessRequestData
{
    public function __construct(
        public string $method,
        public string $route,
        public float  $duration,
        public string $status_code
    )
    {
    }

    public static function from(
        string $method,
        string $route,
        float  $duration,
        int    $status_code
    ): ProcessRequestData
    {
        return new self($method, $route, $duration, $status_code);
    }

    public function toArray(): array{
        return [
            'method' => $this->method,
            'route' => $this->route,
            'duration' => $this->duration,
            'status_code' => $this->status_code
        ];
    }

}
