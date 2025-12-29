<?php

namespace App\Http\Controllers;

use App\Data\People;
use App\Services\SWApiService;
use Illuminate\Http\JsonResponse;

class SWApiController extends Controller
{
    public function __construct(
        private readonly SWApiService $SWApiService
    )
    {

    }
    public function planets() : array{
        return $this->SWApiService->getPlanets();
    }

    public function movies() : array{
        return $this->SWApiService->getMovies();
    }

}
