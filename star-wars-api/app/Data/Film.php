<?php

namespace App\Data;

use App\Enums\AppPaths;
use Spatie\LaravelData\Data;

class Film extends Data
{

    public function __construct(
        public string $uid,
        public string $description,
        public string $created,
        public string $edited,
        public array  $starships,
        public array  $vehicles,
        public array  $planets,
        public string $producer,
        public string $title,
        public int    $episode_id,
        public string $director,
        public string $release_date,
        public string $opening_crawl,
        public array  $characters,
        public array  $species,
        public string $url,
    )
    {
    }

    public static function fromSWApiResponse(array $swApiResponse): self
    {
        $characters = array_map(function ($characterUrl) {
            $id = basename($characterUrl);
            return config('app.front_end_url') . AppPaths::PERSON->value . '/' . $id;
        }, $swApiResponse['properties']['characters'] ?? []);
        return new self(
            uid: $swApiResponse['uid'],
            description: $swApiResponse['description'],
            created: $swApiResponse['properties']['created'],
            edited: $swApiResponse['properties']['edited'],
            starships: $swApiResponse['properties']['starships'],
            vehicles: $swApiResponse['properties']['vehicles'],
            planets: $swApiResponse['properties']['planets'],
            producer: $swApiResponse['properties']['producer'],
            title: $swApiResponse['properties']['title'],
            episode_id: $swApiResponse['properties']['episode_id'],
            director: $swApiResponse['properties']['director'],
            release_date: $swApiResponse['properties']['release_date'],
            opening_crawl: $swApiResponse['properties']['opening_crawl'],
            characters: $characters,
            species: $swApiResponse['properties']['species'],
            url: $swApiResponse['properties']['url'],
        );
    }
}
