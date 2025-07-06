<?php

namespace App\Modules\Pet\src\Infrastructure\Http\Controllers;

use App\Modules\Pet\src\Application\Handlers\UpdatePetHandler;
use App\Modules\Pet\src\Domain\Aggregates\Pet;
use App\Modules\Pet\src\Infrastructure\Http\Requests\UpdatePetRequest;
use App\Modules\Pet\src\Infrastructure\Http\Responses\PetApiResponse;
use Exception;

readonly class UpdatePetController
{
    public function __construct(
       private UpdatePetHandler $handler,
    ) {
    }

    public function __invoke(UpdatePetRequest $request): PetApiResponse
    {
        try {
            $pet = Pet::fromArray($request->toArray());;
            $response = ($this->handler)($pet);
            if(!$response) {
                return PetApiResponse::notFound();
            }

            return PetApiResponse::success(
                message: trans('actions.success'),
                data: $response->toArray()
            );
        } catch (Exception $e) {
            return PetApiResponse::error($e->getMessage());
        }
    }
}
