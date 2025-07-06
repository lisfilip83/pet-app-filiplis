<?php

namespace App\Modules\Pet\src\Infrastructure\Http\Controllers;

use App\Modules\Pet\src\Application\Handlers\GetPetHandler;
use App\Modules\Pet\src\Domain\Data\ValueObjects\PetId;
use App\Modules\Pet\src\Infrastructure\Http\Responses\PetApiResponse;
use Exception;

readonly class GetPetController
{
    public function __construct(
       private GetPetHandler $handler,
    ) {
    }

    public function __invoke(int $id): PetApiResponse
    {
        try {
            $response = ($this->handler)(PetId::create($id));
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
