<?php

namespace App\Data;
use Spatie\LaravelData\Data;

class BaseResponse extends Data
{
    public function __construct(
        public ?int $total,
        public ?int $pages,
        public ?string $previous,
        public ?string $next,
    ){
    }
}
