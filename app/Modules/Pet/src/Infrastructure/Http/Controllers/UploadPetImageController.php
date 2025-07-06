<?php

namespace App\Modules\Pet\src\Infrastructure\Http\Controllers;

use App\Modules\Pet\src\Application\Handlers\UploadPetImageHandler;
use App\Modules\Pet\src\Domain\Data\ValueObjects\PetId;
use App\Modules\Pet\src\Domain\Data\ValueObjects\PetPhotoUrl;
use App\Modules\Pet\src\Infrastructure\Http\Requests\UploadPetImageRequest;
use App\Modules\Pet\src\Infrastructure\Http\Responses\PetApiResponse;
use Exception;

readonly class UploadPetImageController
{
    public function __construct(
       private UploadPetImageHandler $handler,
    ) {
    }

    public function __invoke(int $id, UploadPetImageRequest $request): PetApiResponse
    {
        try {
            $response = ($this->handler)(
                id: PetId::create($id),
                photoUrl: PetPhotoUrl::create($request->getUrl())
            );
            if(!$response) {
                return PetApiResponse::notFound();
            }

            return PetApiResponse::success((string) $id);
        } catch (Exception $e) {
            return PetApiResponse::error($e->getMessage());
        }
    }
}
