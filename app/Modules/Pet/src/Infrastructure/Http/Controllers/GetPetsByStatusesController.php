<?php

namespace App\Modules\Pet\src\Infrastructure\Http\Controllers;

use App\Modules\Pet\src\Application\Handlers\GetPetsByStatusesHandler;
use App\Modules\Pet\src\Domain\Aggregates\Pet;
use App\Modules\Pet\src\Domain\Data\Collections\PetCollection;
use App\Modules\Pet\src\Domain\Data\Collections\PetStatusCollection;
use App\Modules\Pet\src\Infrastructure\Http\Requests\GetPetsByStatusesRequest;
use App\Modules\Pet\src\Infrastructure\Http\Responses\PetApiResponse;
use Exception;

readonly class GetPetsByStatusesController
{
    public function __construct(
       private GetPetsByStatusesHandler $handler,
    ) {
    }

    public function __invoke(GetPetsByStatusesRequest $request): PetApiResponse
    {
//        try {
            $statuses = PetStatusCollection::create($request->getStatuses());
            /**
             * @var PetCollection<int, Pet> $response
             */
            $response = ($this->handler)($statuses);

            return PetApiResponse::success(
                message: trans('actions.success'),
                data: $response->toArray()
            );
//        } catch (Exception $e) {
//            return PetApiResponse::error($e->getMessage());
//        }
    }
}
