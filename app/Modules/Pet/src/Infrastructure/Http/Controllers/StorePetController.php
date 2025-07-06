<?php

namespace App\Modules\Pet\src\Infrastructure\Http\Controllers;

use App\Modules\Pet\src\Application\Handlers\StorePetHandler;
use App\Modules\Pet\src\Domain\Aggregates\Pet;
use App\Modules\Pet\src\Infrastructure\Http\Requests\StorePetRequest;
use App\Modules\Pet\src\Infrastructure\Http\Responses\PetApiResponse;
use Exception;

readonly class StorePetController
{
    public function __construct(
       private StorePetHandler $handler,
    ) {
    }

    public function __invoke(StorePetRequest $request): PetApiResponse
    {
        try {
            $pet = Pet::fromArray($request->toArray());
            $response = ($this->handler)($pet);

            return PetApiResponse::success(
                message: trans('actions.success'),
                data: $response->toArray()
            );
        } catch (Exception $e) {
            return PetApiResponse::error($e->getMessage());
        }
    }
}
