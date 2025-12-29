<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class MostSearchedTermData extends Data
{
    public function __construct(
        public string $search_term,
        public int $total_searches,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            search_term: $data['search_term'] ?? '',
            total_searches: (int) ($data['total_searches'] ?? 0),
        );
    }
}
