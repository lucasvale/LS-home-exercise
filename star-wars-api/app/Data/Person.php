<?php

namespace App\Data;

use App\Enums\AppPaths;
use Spatie\LaravelData\Data;

class Person extends Data
{
    public function __construct(
        public readonly  string $uid,
        public readonly  string $name,
        public readonly  string $url,
        public readonly  ?string $description = null,
        public readonly  ?string $created = null,
        public readonly  ?string $edited = null,
        public readonly  ?string $gender = null,
        public readonly  ?string $skin_color = null,
        public readonly  ?string $hair_color = null,
        public readonly  ?string $height = null,
        public readonly  ?string $eye_color = null,
        public readonly  ?string $mass = null,
        public readonly  ?string $homeworld = null,
        public readonly  ?string $birth_year = null,
        public readonly  ?array $vehicles = null,
        public readonly  ?array $starships = null,
        public readonly  ?array $films = null,
    )
    {
    }
    public static function fromSWApiResponse(array $swApiResponse): self {

        $films = array_map(function ($filmUrl) {
            $id = basename($filmUrl);
            return config('app.front_end_url') . AppPaths::FILM->value . '/' . $id;
        }, $swApiResponse['result']['properties']['films'] ?? []);
        return new self(
            uid: $swApiResponse['result']['uid'],
            name: $swApiResponse['result']['properties']['name'],
            url: $swApiResponse['result']['properties']['url'],
            description: $swApiResponse['result']['description'],
            created: $swApiResponse['result']['properties']['created'],
            edited: $swApiResponse['result']['properties']['edited'],
            gender: $swApiResponse['result']['properties']['gender'],
            skin_color: $swApiResponse['result']['properties']['skin_color'],
            hair_color: $swApiResponse['result']['properties']['hair_color'],
            height: $swApiResponse['result']['properties']['height'],
            eye_color: $swApiResponse['result']['properties']['eye_color'],
            mass: $swApiResponse['result']['properties']['mass'],
            homeworld: $swApiResponse['result']['properties']['homeworld'],
            birth_year: $swApiResponse['result']['properties']['birth_year'],
            vehicles: $swApiResponse['result']['properties']['vehicles'],
            starships: $swApiResponse['result']['properties']['starships'],
            films: $films,
        );

    }
}
