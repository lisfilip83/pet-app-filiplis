<?php

namespace App\Modules\Pet\src\Application\Handlers;

use App\Modules\Pet\src\Domain\Data\ValueObjects\PetId;
use App\Modules\Pet\src\Domain\Data\ValueObjects\PetPhotoUrl;
use App\Modules\Pet\src\Domain\Repositories\PetRepository;

final readonly class UploadPetImageHandler
{
    public function __construct(
        private PetRepository $repository
    ) {
    }

    public function __invoke(PetId $id, PetPhotoUrl $photoUrl): bool
    {
        return $this->repository->addImageUrl($id, $photoUrl);
    }
}
