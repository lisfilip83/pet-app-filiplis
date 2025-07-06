<?php

namespace App\Modules\Pet\src\Infrastructure\Http\Controllers;

use App\Modules\Pet\src\Application\Handlers\DeletePetHandler;
use App\Modules\Pet\src\Domain\Data\ValueObjects\PetId;
use App\Modules\Pet\src\Infrastructure\Http\Responses\PetApiResponse;
use Exception;

readonly class DeletePetController
{
    public function __construct(
       private DeletePetHandler $handler,
    ) {
    }

    public function __invoke(int $id): PetApiResponse
    {
        try {
            $response = ($this->handler)(PetId::create($id));
            if(!$response) {
                return PetApiResponse::notFound();
            }

            return PetApiResponse::success($id);
        } catch (Exception $e) {
            return PetApiResponse::error($e->getMessage());
        }
    }
}
