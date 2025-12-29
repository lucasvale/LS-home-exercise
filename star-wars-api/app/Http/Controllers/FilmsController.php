<?php

namespace App\Http\Controllers;

use App\Data\Request\FindFilmsRequest;
use App\Enums\HttpStatus;
use App\Services\FilmsService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;

class FilmsController extends ApiController
{
    public function __construct(
        private readonly FilmsService $filmsService
    )
    {
    }

    /**
     * @throws ConnectionException
     */
    public function getFilms(FindFilmsRequest $request): JsonResponse
    {
        $films = $this->filmsService->getFilms($request);
        if (count($films->results) === 0 ){
            return $this->failResponse([], HttpStatus::HTTP_NOT_FOUND);
        }
        return $this->successResponse($films->results);
    }

    public function getFilmsBtId(string $id): JsonResponse
    {
        if (empty($id)){
            return $this->errorResponse('Provide the id attribute', [], HttpStatus::HTTP_BAD_REQUEST);
        }
        $films = $this->filmsService->getFilmsById($id);
        if (count($films->results) === 0 ){
            return $this->failResponse([], HttpStatus::HTTP_NOT_FOUND);
        }
        return $this->successResponse($films->results);
    }
}
