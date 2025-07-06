<?php

namespace App\Modules\Pet\src\Application\Handlers;

use App\Modules\Pet\src\Domain\Aggregates\Pet;
use App\Modules\Pet\src\Domain\Data\ValueObjects\PetId;
use App\Modules\Pet\src\Domain\Repositories\PetRepository;

final readonly class GetPetHandler
{
    public function __construct(
        private PetRepository $repository
    ) {
    }

    public function __invoke(PetId $id): ?Pet
    {
        return $this->repository->findById($id);
    }
}
