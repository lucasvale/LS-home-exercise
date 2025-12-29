<?php

namespace App\Clients\Http\External;

use App\Data\Films;
use App\Data\People;
use App\Data\Request\FindFilmsRequest;
use App\Data\Request\FindPeopleRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

readonly class SWApi
{
    private string  $base_url;
    public function __construct()
    {
        $this->base_url = config('services.swapi.base_uri');
    }

    /**
     * @throws ConnectionException
     */
    public function getPeople(FindPeopleRequest $request): People
    {
        $url = $this->base_url.\App\Enums\SWApi::PEOPLE->value;
        $query_parameters = [];
        if (!empty($request->name)){
            $query_parameters['name'] = $request->name;
        }
        $response = Http::withQueryParameters($query_parameters)->get($url);
        $data = $response->json();
        return People::fromSWApiResponse($data);
    }

    public function getPeopleById(string $id): People
    {
        $url = $this->base_url.\App\Enums\SWApi::PEOPLE->value;
        if (!empty($id)){
            $url .= '/'.$id;
        }
        $response = Http::get($url);
        $data = $response->json();
        return People::fromSWApiResponse($data);
    }

    /**
     * @throws ConnectionException
     */
    public function getFilms(FindFilmsRequest $request): Films
    {
        $url = $this->base_url.\App\Enums\SWApi::FILMS->value;
        $url_parameters = [];
        if (!empty($request->title)){
            $url_parameters['title'] = $request->title;
        }
        $response = Http::withQueryParameters($url_parameters)->get($url);
        $data = $response->json();
        return Films::fromSWApiResponse($data);
    }

    public function getFilmsById(string $id): Films
    {
        $url = $this->base_url.\App\Enums\SWApi::FILMS->value;
        if (!empty($id)){
            $url .= '/'.$id;
        }
        $response = Http::get($url);
        $data = $response->json();
        return Films::fromSWApiResponse($data);
    }

}
