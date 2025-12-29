<?php

namespace App\Data;

use App\Data\Person;

class Films extends BaseResponse
{
    public function __construct(
        public ?int    $total,
        public ?int    $pages,
        public ?string $previous,
        public ?string $next,
        /** @var Film[] */
        public array  $results
    )
    {
        parent::__construct(
            $total,
            $pages,
            $previous,
            $next,
        );
    }

    public static function fromSWApiResponse(array $swApiResponse): self
    {
        $results = $swApiResponse['results'] ?? $swApiResponse['result'] ?? null;
        if (isset($results) && array_is_list($results)) {
            $results = array_map(
                fn(array $film) => Film::fromSWApiResponse($film),
                $results ?? []
            );
        }
        if (isset($results) && !array_is_list($results)){
            $temp[] = Film::fromSWApiResponse($results);
            $results = $temp ?? [];
        }


        return new self(
            $swApiResponse['total_records'] ?? null,
            $swApiResponse['total_pages'] ?? null,
            $swApiResponse['previous'] ?? null,
            $swApiResponse['next'] ?? null,
            $results
        );
    }

}
