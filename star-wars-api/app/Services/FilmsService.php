<?php

namespace App\Services;

use App\Data\Films;
use App\Data\People;
use App\Data\Request\FindFilmsRequest;
use Illuminate\Http\Client\ConnectionException;

readonly class FilmsService
{

    public function __construct(
        private SWApiService $SWApiService,
    )
    {

    }

    /**
     * @throws ConnectionException
     */
    public function getFilms(FindFilmsRequest $request): Films
    {
        return $this->SWApiService->getFilms($request);
    }

    public function getFilmsById(string $id): Films
    {
        return $this->SWApiService->getFilmsById($id);
    }

}
