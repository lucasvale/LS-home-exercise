<?php

namespace App\Http\Controllers;

use App\Data\Request\FindPeopleRequest;
use App\Enums\HttpStatus;
use App\Services\PeopleService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;

class PeopleController extends ApiController
{
    public function __construct(
        private readonly PeopleService $peopleService
    )
    {
    }

    /**
     * @throws ConnectionException
     */
    public function getPeople(FindPeopleRequest $request): JsonResponse
    {
        $people = $this->peopleService->getPeople($request);
        if (count($people->results) === 0) {
            return $this->failResponse([], HttpStatus::HTTP_NOT_FOUND);
        }
        return $this->successResponseWithMeta(
            $people->results,
            [
                'total' => $people->total,
                'pages' => $people->pages,
                'previous' => $people->previous,
                'next' => $people->next,
            ]
        );
    }

    public function getPeopleById(string $id): JsonResponse
    {
        if (empty($id)){
            return $this->errorResponse('Provide the id attribute', [], HttpStatus::HTTP_BAD_REQUEST);
        }
        $people = $this->peopleService->getById($id);
        if (count($people->results) === 0) {
            return $this->failResponse([], HttpStatus::HTTP_NOT_FOUND);
        }
        return $this->successResponse($people->results);
    }
}
