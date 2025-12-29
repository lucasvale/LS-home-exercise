<?php

namespace App\Clients\Http\External;

use App\Data\Films;
use App\Data\People;
use App\Data\Request\FindFilmsRequest;
use App\Data\Request\FindPeopleRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

readonly class SWApi
{
    private string $base_url;
    private string $base_key_cache;
    private int $expiration_time;

    public function __construct()
    {
        $this->base_url = config('services.swapi.base_uri');
        $this->base_key_cache = 'external_api_data:';
        $this->expiration_time = 60;
    }

    /**
     * @throws ConnectionException
     */
    public function getPeople(FindPeopleRequest $request): People
    {
        $url = $this->base_url . \App\Enums\SWApi::PEOPLE->value;
        $query_parameters = [];
        $key = $this->base_key_cache . $url;
        if (!empty($request->name)) {
            $query_parameters['name'] = $request->name;
            $key .= $request->name;
        }
        $data = Cache::remember(md5($key), $this->expiration_time, function () use ($url, $query_parameters) {
            $response = Http::withQueryParameters($query_parameters)->get($url);
            return $response->json();
        });
        return People::fromSWApiResponse($data);
    }

    public function getPeopleById(string $id): People
    {
        $url = $this->base_url . \App\Enums\SWApi::PEOPLE->value;
        if (!empty($id)) {
            $url .= '/' . $id;
        }
        $key = $this->base_key_cache . $url;
        $data = Cache::remember(md5($key), $this->expiration_time, function () use ($url) {
            $response = Http::get($url);
            return $response->json();
        });
        return People::fromSWApiResponse($data);
    }

    /**
     * @throws ConnectionException
     */
    public function getFilms(FindFilmsRequest $request): Films
    {
        $url = $this->base_url . \App\Enums\SWApi::FILMS->value;
        $query_parameters = [];
        $key = $this->base_key_cache . $url;
        if (!empty($request->title)) {
            $query_parameters['title'] = $request->title;
            $key .= $request->title;
        }
        $data = Cache::remember(md5($key), $this->expiration_time, function () use ($url, $query_parameters) {
            $response = Http::withQueryParameters($query_parameters)->get($url);
            return $response->json();
        });
        return Films::fromSWApiResponse($data);
    }

    public function getFilmsById(string $id): Films
    {
        $url = $this->base_url . \App\Enums\SWApi::FILMS->value;
        if (!empty($id)) {
            $url .= '/' . $id;
        }
        $key = $this->base_key_cache . $url;
        $data = Cache::remember(md5($key), $this->expiration_time, function () use ($url) {
            $response = Http::get($url);
            return $response->json();
        });
        return Films::fromSWApiResponse($data);
    }

}
