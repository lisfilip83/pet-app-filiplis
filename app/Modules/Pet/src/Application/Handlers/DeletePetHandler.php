<?php

namespace App\Modules\Pet\src\Application\Handlers;

use App\Modules\Pet\src\Domain\Data\ValueObjects\PetId;
use App\Modules\Pet\src\Domain\Repositories\PetRepository;

final readonly class DeletePetHandler
{
    public function __construct(
        private PetRepository $repository
    ) {
    }

    public function __invoke(PetId $id): bool
    {
        return $this->repository->delete($id);
    }
}
