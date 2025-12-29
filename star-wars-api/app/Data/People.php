<?php

namespace App\Data;
class People extends BaseResponse
{
    public function __construct(
        public ?int    $total,
        public ?int    $pages,
        public ?string $previous,
        public ?string $next,
        /** @var Person[] */
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
                fn(array $person) => new Person(
                    uid: $person['uid'],
                    name: $person['properties']['name'] ?? $person['name'],
                    url: $person['properties']['name'] ?? $person['url']
                ),
                $results ?? []
            );
        }

        if (isset($results) && !array_is_list($results)) {
            $results = [];
            $results[] = Person::fromSWApiResponse($swApiResponse);
        }

        return new self(
            $swApiResponse['total_records'] ?? count($results),
            $swApiResponse['total_pages'] ?? 1,
            $swApiResponse['previous'] ?? null,
            $swApiResponse['next'] ?? null,
            $results
        );
    }

}
