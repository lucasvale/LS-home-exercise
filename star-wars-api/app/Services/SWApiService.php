<?php

namespace App\Services;

use \App\Clients\Http\External\SWApi;
use App\Data\Films;
use App\Data\People;
use App\Data\Request\FindFilmsRequest;
use App\Data\Request\FindPeopleRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;

readonly class SWApiService
{

    public function __construct(
        private SWApi $SWApi
    )
    {
    }
    public function getPlanets(): array
    {
        return $this->SWApi->getPlanets();
    }

    /**
     * @throws ConnectionException
     */
    public function getFilms(FindFilmsRequest $request): Films
    {
        return $this->SWApi->getFilms($request);
    }

    public function getFilmsById(string $id): Films
    {
        return $this->SWApi->getFilmsById($id);
    }

    /**
     * @throws ConnectionException
     */
    public function getPeople(FindPeopleRequest $request): People
    {
        return $this->SWApi->getPeople($request);
    }

    public function getPeopleById(string $id): People
    {
        return $this->SWApi->getPeopleById($id);
    }


}
