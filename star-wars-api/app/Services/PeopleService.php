<?php

namespace App\Services;

use App\Data\People;
use App\Data\Request\FindPeopleRequest;
use Illuminate\Http\Client\ConnectionException;

readonly class PeopleService
{

    public function __construct(
        private SWApiService $SWApiService,
    )
    {

    }

    /**
     * @throws ConnectionException
     */
    public function getPeople(FindPeopleRequest $request): People
    {
        return $this->SWApiService->getPeople($request);
    }

    public function getById(string $id): People
    {
        return $this->SWApiService->getPeopleById($id);
    }

}
